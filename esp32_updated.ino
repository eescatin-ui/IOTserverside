// ==========================================
//  COMPLETE IoT SYSTEM - DUAL LED VERSION
//  WITH BUZZER DURATION CONTROL (2s, 4s, 6s)
//  Yellow LED (D12): ON when LIGHT
//  Red LED (D14): ON when DARK
//  Buzzer: configurable 2s/4s/6s when MOTION
//  FIXED: HTTP timeout increased to 5s for stability
//  FIXED: Send interval set to 3s to prevent server overload
// ==========================================

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// WiFi Configuration
const char* ssid = "PLDTHOMEFIBRABy6E";
const char* password = "HhEeLlEeNn_1986!";
const char* serverUrl = "http://192.168.1.250:8000/api";

// Pin Definitions
#define LDR_PIN 25          // LDR sensor
#define YELLOW_LED_PIN 12   // Yellow LED - glows when LIGHT
#define RED_LED_PIN 14      // Red LED - glows when DARK
#define MOTION_PIN 33       // Motion sensor
#define BUZZER_PIN 32       // Buzzer

// Timing Variables
unsigned long lastSendTime = 0;
const unsigned long sendInterval = 3000;  // Send every 3 seconds (reduced server load)

unsigned long lastPollTime = 0;
const unsigned long pollInterval = 5000;  // Poll every 5 seconds (less frequent)

// Actuator enable states (from mobile app)
bool yellowLedEnabled = true;
bool redLedEnabled = true;
bool buzzerEnabled = true;

// Buzzer duration control (1=2s, 2=4s, 3=6s)
int buzzerDurationSeconds = 2;  // Default: 2 (4 seconds)
unsigned long buzzerDuration = 4000;  // milliseconds

// Buzzer timing
bool buzzerActive = false;
unsigned long buzzerStartTime = 0;

// Motion hold count to keep motion TRUE for multiple sends
int motionHoldCount = 0;
const int motionHoldCountMax = 9;  // Hold motion for ~9 send cycles (27 seconds) to ensure registration

// Track motion detection separately from sending
bool motionDetected = false;
unsigned long motionDetectedTime = 0;

// ==========================================
//  SETUP
// ==========================================

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  pinMode(LDR_PIN, INPUT);
  pinMode(MOTION_PIN, INPUT);
  pinMode(YELLOW_LED_PIN, OUTPUT);
  pinMode(RED_LED_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  
  // Initialize all outputs OFF
  digitalWrite(YELLOW_LED_PIN, LOW);
  digitalWrite(RED_LED_PIN, LOW);
  noTone(BUZZER_PIN);
  
  Serial.println("=========================================");
  Serial.println("ESP32 IoT SYSTEM - DUAL LED VERSION");
  Serial.println("WITH BUZZER DURATION CONTROL");
  Serial.println("=========================================");
  Serial.println("Yellow LED (D12): ON when LIGHT");
  Serial.println("Red LED (D14): ON when DARK");
  Serial.println("Buzzer Duration: 2s, 4s, or 6s (configurable)");
  Serial.println("HTTP Timeout: 5 seconds (increased for stability)");
  Serial.println("Send Interval: 3 seconds (reduced server load)");
  Serial.println("=========================================");
  
  connectToWiFi();
  
  Serial.println("\n⏳ Waiting 5 seconds for motion sensor...");
  for (int i = 0; i < 5; i++) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("\n✅ Ready!");
  Serial.println("=========================================\n");
}

void connectToWiFi() {
  WiFi.begin(ssid, password);
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 30) {
    delay(1000);
    Serial.print(".");
    attempts++;
  }
  Serial.println("\n✅ WiFi Connected!");
}

// ==========================================
//  UPDATE BUZZER DURATION BASED ON SETTING
// ==========================================

void updateBuzzerDuration() {
  switch(buzzerDurationSeconds) {
    case 1:
      buzzerDuration = 2000;   // 2 seconds
      Serial.println("🔊 Buzzer duration: 2 SECONDS");
      break;
    case 2:
      buzzerDuration = 4000;   // 4 seconds
      Serial.println("🔊 Buzzer duration: 4 SECONDS");
      break;
    case 3:
      buzzerDuration = 6000;   // 6 seconds
      Serial.println("🔊 Buzzer duration: 6 SECONDS");
      break;
    default:
      buzzerDuration = 4000;
      Serial.println("🔊 Buzzer duration: DEFAULT 4 SECONDS");
  }
}

// ==========================================
//  POLL ACTUATOR ENABLE STATES FROM SERVER
// ==========================================

void pollActuatorStates() {
  if (WiFi.status() != WL_CONNECTED) return;
  
  HTTPClient http;
  http.begin(serverUrl + String("/actuators/status"));
  http.setTimeout(5000);  // Increased timeout for stability
  
  int httpCode = http.GET();
  
  if (httpCode == HTTP_CODE_OK) {
    String payload = http.getString();
    StaticJsonDocument<192> doc;
    deserializeJson(doc, payload);
    
    // Read enable states from database
    bool newYellowLedEnabled = doc["yellow_led"] == 1;
    bool newRedLedEnabled = doc["red_led"] == 1;
    bool newBuzzerEnabled = doc["buzzer"] == 1;
    
    if (newYellowLedEnabled != yellowLedEnabled) {
      yellowLedEnabled = newYellowLedEnabled;
      Serial.printf("📱 Yellow LED: %s\n", yellowLedEnabled ? "ENABLED" : "DISABLED");
      if (!yellowLedEnabled) digitalWrite(YELLOW_LED_PIN, LOW);
    }
    
    if (newRedLedEnabled != redLedEnabled) {
      redLedEnabled = newRedLedEnabled;
      Serial.printf("📱 Red LED: %s\n", redLedEnabled ? "ENABLED" : "DISABLED");
      if (!redLedEnabled) digitalWrite(RED_LED_PIN, LOW);
    }
    
    if (newBuzzerEnabled != buzzerEnabled) {
      buzzerEnabled = newBuzzerEnabled;
      Serial.printf("📱 Buzzer: %s\n", buzzerEnabled ? "ENABLED" : "DISABLED");
      if (!buzzerEnabled) {
        noTone(BUZZER_PIN);
        buzzerActive = false;
      }
    }
  } else {
    Serial.printf("⚠️ Actuator poll failed: HTTP %d\n", httpCode);
  }
  http.end();
}

// ==========================================
//  POLL BUZZER DURATION FROM SERVER
// ==========================================

void pollBuzzerDuration() {
  if (WiFi.status() != WL_CONNECTED) return;
  
  HTTPClient http;
  http.begin(serverUrl + String("/esp32/duration"));
  http.setTimeout(5000);
  
  int httpCode = http.GET();
  
  Serial.printf("🔍 Polling duration - HTTP Code: %d\n", httpCode);
  
  if (httpCode == HTTP_CODE_OK) {
    String payload = http.getString();
    Serial.printf("📦 Duration Response: %s\n", payload.c_str());
    
    StaticJsonDocument<64> doc;
    DeserializationError error = deserializeJson(doc, payload);
    
    if (!error) {
      int newDuration = doc["duration"] | 2;  // Default to 2 (4 seconds)
      Serial.printf("📊 Parsed duration value: %d\n", newDuration);
      
      if (newDuration >= 1 && newDuration <= 3) {
        if (newDuration != buzzerDurationSeconds) {
          buzzerDurationSeconds = newDuration;
          updateBuzzerDuration();
          Serial.printf("✅ Duration updated to: %d (%lu ms)\n", buzzerDurationSeconds, buzzerDuration);
        }
      } else {
        Serial.printf("⚠️ Invalid duration value: %d\n", newDuration);
      }
    } else {
      Serial.println("❌ Failed to parse JSON");
    }
  } else {
    Serial.printf("❌ Duration poll failed - HTTP %d\n", httpCode);
  }
  
  http.end();
}

// ==========================================
//  SEND SENSOR DATA TO SERVER
// ==========================================

void sendSensorData() {
  if (WiFi.status() != WL_CONNECTED) return;
  
  int lightRaw = digitalRead(LDR_PIN);
  int motionRaw = digitalRead(MOTION_PIN);

  // Set motion hold when motion is detected
  if (motionRaw == HIGH) {
    motionHoldCount = motionHoldCountMax;
    motionDetected = true;
    motionDetectedTime = millis();
  }

  // Refresh hold counter while buzzer is still active (motion is ongoing)
  if (buzzerActive) {
    motionHoldCount = max(motionHoldCount, 3);  // At least 3 more sends while buzzer is active
  }

  // Use motion hold to keep sending 1 for several cycles
  int motionValue = 0;
  if (motionHoldCount > 0) {
    motionValue = 1;
    motionHoldCount--;
  }
  
  int lightValue = (lightRaw == LOW) ? 1 : 0;
  
  Serial.println("─────────────────────────────────────────");
  Serial.printf("📊 Light: %d (raw: %d)\n", lightValue, lightRaw);
  Serial.printf("📊 Motion raw: %d  hold=%d  send=%d\n", motionRaw, motionHoldCount, motionValue);
  Serial.printf("📊 Buzzer active: %d  duration: %lu ms\n", buzzerActive ? 1 : 0, buzzerDuration);
  Serial.println("─────────────────────────────────────────");
  
  StaticJsonDocument<128> jsonDoc;
  jsonDoc["light"] = lightValue;
  jsonDoc["motion"] = motionValue;
  
  String jsonString;
  serializeJson(jsonDoc, jsonString);
  
  HTTPClient http;
  http.begin(serverUrl + String("/sensors/data"));
  http.addHeader("Content-Type", "application/json");
  http.setTimeout(5000);
  
  int httpCode = http.POST(jsonString);
  
  if (httpCode == HTTP_CODE_CREATED || httpCode == HTTP_CODE_OK) {
    Serial.println("✅ Data saved!");
  } else {
    Serial.printf("❌ HTTP Error: %d\n", httpCode);
  }
  
  http.end();
}

// ==========================================
//  CONTROL HARDWARE - RESPECTS ENABLE STATES
// ==========================================

void controlHardware() {
  int lightRaw = digitalRead(LDR_PIN);
  int motionRaw = digitalRead(MOTION_PIN);
  
  bool isLight = (lightRaw == LOW);
  bool isDark = (lightRaw == HIGH);
  
  // ==========================================
  // YELLOW LED (D12): ON when LIGHT
  // ==========================================
  if (yellowLedEnabled) {
    digitalWrite(YELLOW_LED_PIN, isLight ? HIGH : LOW);
  } else {
    digitalWrite(YELLOW_LED_PIN, LOW);
  }
  
  // ==========================================
  // RED LED (D14): ON when DARK
  // ==========================================
  if (redLedEnabled) {
    digitalWrite(RED_LED_PIN, isDark ? HIGH : LOW);
  } else {
    digitalWrite(RED_LED_PIN, LOW);
  }
  
  // ==========================================
  // BUZZER (D32): ON for configured duration when MOTION
  // ==========================================
  if (buzzerEnabled) {
    bool isMotion = (motionRaw == HIGH);
    
    if (isMotion && !buzzerActive) {
      // Motion detected - Activate buzzer
      buzzerActive = true;
      buzzerStartTime = millis();
      tone(BUZZER_PIN, 1000);
      Serial.printf("🔴🔴🔴 MOTION! → Buzzer ON for %d seconds (%lu ms) 🔴🔴🔴\n", buzzerDurationSeconds, buzzerDuration);
    }
    
    // Check if it's time to turn off buzzer
    if (buzzerActive && (millis() - buzzerStartTime >= buzzerDuration)) {
      buzzerActive = false;
      noTone(BUZZER_PIN);
      Serial.println("✓ Buzzer OFF");
    }
  } else {
    // Buzzer disabled
    noTone(BUZZER_PIN);
    buzzerActive = false;
  }
}

// ==========================================
//  MAIN LOOP - FIXED TIMING
// ==========================================

void loop() {
  // Control hardware and check motion EVERY loop cycle
  controlHardware();
  
  // Send sensor data (every 3 seconds - reduced from 1s to prevent server overload)
  if (millis() - lastSendTime >= sendInterval) {
    sendSensorData();
    lastSendTime = millis();
  }
  
  // Poll server (every 5 seconds - fewer polls = less server load)
  if (millis() - lastPollTime >= pollInterval) {
    pollActuatorStates();
    delay(200);  // Small delay between poll requests
    pollBuzzerDuration();
    lastPollTime = millis();
  }
  
  delay(100);
}