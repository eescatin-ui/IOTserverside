<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURA • Admin Console</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0D0B1E;
            --bg-secondary: #13102A;
            --bg-card: #1A1538;
            --border-color: rgba(139, 92, 246, 0.15);
            --border-glow: rgba(139, 92, 246, 0.3);
            --accent-violet: #8B5CF6;
            --accent-violet-light: #A78BFA;
            --accent-violet-dark: #6D28D9;
            --accent-pink: #EC4899;
            --accent-pink-light: #F472B6;
            --accent-cyan: #22D3EE;
            --accent-green: #34D399;
            --accent-red: #F87171;
            --accent-amber: #FBBF24;
            --accent-orange: #FB923C;
            --text-primary: #F5F3FF;
            --text-secondary: #C4B5FD;
            --text-muted: #8B7EC8;
            --surface: rgba(139, 92, 246, 0.08);
            --surface-glow: rgba(139, 92, 246, 0.2);
            --gradient-violet-pink: linear-gradient(135deg, #8B5CF6, #EC4899);
            --glow-violet: 0 0 20px rgba(139, 92, 246, 0.3);
            --glow-pink: 0 0 20px rgba(236, 72, 153, 0.3);
            --glow-red: 0 0 20px rgba(248, 113, 113, 0.3);
            --glow-amber: 0 0 20px rgba(251, 191, 36, 0.3);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -100px;
            left: -50%;
            width: 200%;
            height: 500px;
            background: radial-gradient(ellipse at center, rgba(139, 92, 246, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            animation: ambientGlow 8s ease-in-out infinite alternate;
        }

        @keyframes ambientGlow {
            0% { opacity: 0.5; transform: translateY(0); }
            100% { opacity: 1; transform: translateY(-20px); }
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -100px;
            right: -30%;
            width: 150%;
            height: 400px;
            background: radial-gradient(ellipse at center, rgba(236, 72, 153, 0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            animation: ambientGlow2 10s ease-in-out infinite alternate;
        }

        @keyframes ambientGlow2 {
            0% { opacity: 0.3; transform: translateY(0); }
            100% { opacity: 0.7; transform: translateY(20px); }
        }

        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Header */
        .header {
            background: rgba(26, 21, 56, 0.8);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            border-bottom: 2px solid var(--border-glow);
            transition: border-bottom-color 0.6s;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent-violet), var(--accent-pink), transparent);
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .headline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(236, 72, 153, 0.2));
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: var(--glow-violet);
        }

        h1 {
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-violet-light), var(--accent-pink-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            margin-top: -2px;
        }

        .last-update {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 12px;
            font-weight: 500;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid transparent;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.3);
            color: var(--accent-violet-light);
        }

        .btn-primary:hover {
            background: rgba(139, 92, 246, 0.25);
            border-color: var(--accent-violet);
            box-shadow: var(--glow-violet);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: rgba(248, 113, 113, 0.12);
            border-color: rgba(248, 113, 113, 0.3);
            color: var(--accent-red);
        }

        .btn-danger:hover {
            background: rgba(248, 113, 113, 0.22);
            box-shadow: 0 0 20px rgba(248, 113, 113, 0.25);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 10px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Syncing Bar */
        .syncing-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 20px;
            color: var(--accent-violet-light);
            font-size: 12px;
            font-weight: 600;
        }

        .syncing-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(139, 92, 246, 0.3);
            border-top-color: var(--accent-violet-light);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Sensor Cards Grid */
        .sensor-cards-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 900px) {
            .sensor-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Sensor Card */
        .sensor-card {
            background: var(--bg-card);
            border: 1.5px solid var(--border-color);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }

        .sensor-card.motion-active {
            border-color: rgba(248, 113, 113, 0.4);
            box-shadow: var(--glow-red);
            animation: cardPulseRed 1s ease-in-out infinite alternate;
        }

        .sensor-card.light-active {
            border-color: rgba(251, 191, 36, 0.4);
            box-shadow: var(--glow-amber);
            animation: cardPulseAmber 1.5s ease-in-out infinite alternate;
        }

        @keyframes cardPulseRed {
            0% { box-shadow: 0 0 15px rgba(248, 113, 113, 0.2); }
            100% { box-shadow: 0 0 30px rgba(248, 113, 113, 0.4); }
        }

        @keyframes cardPulseAmber {
            0% { box-shadow: 0 0 15px rgba(251, 191, 36, 0.2); }
            100% { box-shadow: 0 0 30px rgba(251, 191, 36, 0.4); }
        }

        .card-accent-line {
            height: 3px;
            width: 100%;
            opacity: 0.6;
            background: var(--accent-violet);
            transition: background 0.5s;
        }

        .sensor-card.motion-active .card-accent-line {
            background: var(--accent-red);
        }

        .sensor-card.light-active .card-accent-line {
            background: var(--accent-amber);
        }

        .card-inner {
            padding: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }

        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            border: 1px solid;
        }

        .icon-circle.inactive {
            background: rgba(139, 92, 246, 0.1);
            border-color: rgba(139, 92, 246, 0.2);
        }

        .icon-circle.motion-active {
            background: rgba(248, 113, 113, 0.15);
            border-color: rgba(248, 113, 113, 0.3);
        }

        .icon-circle.light-active {
            background: rgba(251, 191, 36, 0.15);
            border-color: rgba(251, 191, 36, 0.3);
        }

        .icon-circle.dark-active {
            background: rgba(139, 92, 246, 0.12);
            border-color: rgba(139, 92, 246, 0.25);
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .card-status {
            font-size: 13px;
            font-weight: 600;
            margin-top: 2px;
        }

        .sensor-value-badge {
            padding: 10px 16px;
            border-radius: 14px;
            border: 1px solid;
            font-size: 22px;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .badge-inactive {
            background: rgba(139, 92, 246, 0.08);
            border-color: rgba(139, 92, 246, 0.15);
            color: var(--text-secondary);
        }

        .badge-motion {
            background: rgba(248, 113, 113, 0.12);
            border-color: rgba(248, 113, 113, 0.3);
            color: var(--accent-red);
        }

        .badge-light {
            background: rgba(251, 191, 36, 0.12);
            border-color: rgba(251, 191, 36, 0.3);
            color: var(--accent-amber);
        }

        .badge-dark {
            background: rgba(139, 92, 246, 0.08);
            border-color: rgba(139, 92, 246, 0.15);
            color: var(--text-secondary);
        }

        .card-divider {
            height: 1px;
            background: rgba(139, 92, 246, 0.12);
            margin-bottom: 20px;
        }

        .controls-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .controls-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-family: 'JetBrains Mono', monospace;
        }

        .control-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .control-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .control-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .led-indicator {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            box-shadow: 0 0 10px currentColor;
        }

        .control-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .control-hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 56px;
            height: 30px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-track {
            position: absolute;
            inset: 0;
            background: rgba(109, 40, 217, 0.25);
            border-radius: 30px;
            transition: 0.3s;
            border: 2px solid rgba(139, 92, 246, 0.2);
        }

        .toggle-track::before {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            left: 2px;
            bottom: 2px;
            background: var(--text-muted);
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked + .toggle-track {
            background: linear-gradient(135deg, var(--accent-violet), var(--accent-pink));
            border-color: transparent;
            box-shadow: var(--glow-violet);
        }

        .toggle-switch input:checked + .toggle-track::before {
            transform: translateX(26px);
            background: #fff;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.4);
        }

        .toggle-switch.loading {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Duration Selector */
        .duration-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-family: 'JetBrains Mono', monospace;
            margin-top: 4px;
        }

        .duration-row {
            display: flex;
            gap: 10px;
        }

        .duration-btn {
            flex: 1;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid rgba(139, 92, 246, 0.15);
            background: rgba(139, 92, 246, 0.05);
            color: var(--text-muted);
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Outfit', sans-serif;
            text-align: center;
            position: relative;
        }

        .duration-btn.active {
            background: rgba(139, 92, 246, 0.2);
            border-color: var(--accent-violet);
            color: var(--accent-violet-light);
            box-shadow: 0 4px 16px rgba(139, 92, 246, 0.25);
        }

        .duration-btn.active::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--accent-violet-light);
        }

        .duration-btn:hover:not(.active):not(:disabled) {
            border-color: rgba(139, 92, 246, 0.3);
            color: var(--text-secondary);
        }

        .duration-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .duration-current {
            font-size: 11px;
            color: var(--text-muted);
            text-align: center;
            margin-top: 8px;
            font-weight: 500;
        }

        /* Tables Section */
        .section {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.4), transparent);
            opacity: 0.6;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            letter-spacing: -0.3px;
        }

        /* Tabs */
        .tab-nav {
            display: flex;
            gap: 4px;
            background: rgba(13, 11, 30, 0.5);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            text-align: center;
            transition: all 0.3s;
            color: var(--text-muted);
            border: none;
            background: transparent;
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tab-btn.active {
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent-violet-light);
        }

        .tab-btn:hover:not(.active) {
            color: var(--text-secondary);
        }

        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: rgba(139, 92, 246, 0.06);
        }

        th {
            padding: 12px 16px;
            text-align: left;
            color: var(--accent-violet-light);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'JetBrains Mono', monospace;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.08);
            color: var(--text-primary);
        }

        tbody tr:hover {
            background: rgba(139, 92, 246, 0.04);
        }

        /* Badges */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
            font-family: 'JetBrains Mono', monospace;
        }

        .badge-violet {
            background: rgba(139, 92, 246, 0.15);
            color: var(--accent-violet-light);
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .badge-pink {
            background: rgba(236, 72, 153, 0.15);
            color: var(--accent-pink-light);
            border: 1px solid rgba(236, 72, 153, 0.3);
        }

        .badge-green {
            background: rgba(52, 211, 153, 0.15);
            color: var(--accent-green);
            border: 1px solid rgba(52, 211, 153, 0.3);
        }

        .badge-red {
            background: rgba(248, 113, 113, 0.15);
            color: var(--accent-red);
            border: 1px solid rgba(248, 113, 113, 0.3);
        }

        .badge-amber {
            background: rgba(251, 191, 36, 0.15);
            color: var(--accent-amber);
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        /* Status */
        .status-pulse {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .pulse-dot.online {
            background: var(--accent-green);
            box-shadow: 0 0 10px var(--accent-green);
            animation: pulse 2s infinite;
        }

        .pulse-dot.offline {
            background: var(--accent-red);
            box-shadow: 0 0 10px var(--accent-red);
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(52, 211, 153, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 211, 153, 0); }
        }

        /* Footer */
        .footer-bar {
            background: rgba(139, 92, 246, 0.05);
            border: 1px solid rgba(139, 92, 246, 0.1);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .footer-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent-violet-light);
        }

        /* Toast */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
        }

        .toast {
            padding: 16px 24px;
            border-radius: 14px;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s;
            border: 1px solid;
            font-family: 'Outfit', sans-serif;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background: rgba(52, 211, 153, 0.15);
            border-color: var(--accent-green);
            box-shadow: 0 0 20px rgba(52, 211, 153, 0.2);
        }

        .toast.error {
            background: rgba(248, 113, 113, 0.15);
            border-color: var(--accent-red);
            box-shadow: 0 0 20px rgba(248, 113, 113, 0.2);
        }

        /* Timeout */
        .timeout-banner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            background: rgba(251, 191, 36, 0.12);
            border-bottom: 1px solid var(--accent-amber);
            color: var(--accent-amber);
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            z-index: 999;
            display: none;
            font-family: 'JetBrains Mono', monospace;
        }

        .timeout-banner.active {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }

        /* Login */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 420px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-violet), var(--accent-pink), var(--accent-cyan));
            opacity: 0.7;
        }

        .login-title {
            font-size: 28px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--accent-violet-light), var(--accent-pink-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .input-field {
            width: 100%;
            padding: 14px 16px;
            background: rgba(13, 11, 30, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 14px;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--accent-violet);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.2);
        }

        .input-field::placeholder {
            color: var(--text-muted);
        }

        .input-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--accent-violet-light);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-family: 'JetBrains Mono', monospace;
        }

        .text-muted { color: var(--text-muted); }
        .text-center { text-align: center; }
        .mb-4 { margin-bottom: 16px; }
    </style>
</head>
<body>
<div id="app" class="page" v-cloak>
    <!-- Timeout Warning -->
    <div class="timeout-banner" :class="{ active: showTimeoutWarning }">
        ⚡ Session expires in <strong>@{{ timeoutSeconds }}</strong>s
    </div>

    <!-- Toast -->
    <div class="toast-container">
        <div class="toast" :class="toastClass" v-if="toastMessage">@{{ toastMessage }}</div>
    </div>

    <!-- Login -->
    <div v-if="!token" class="login-container">
        <div class="login-card">
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="font-size: 48px; margin-bottom: 16px;">⚡</div>
                <h1 class="login-title">AURA</h1>
                <p class="text-muted" style="font-size: 14px; margin-top: 8px;">Administrative Console</p>
            </div>
            <div style="margin-bottom: 20px;">
                <label class="input-label">Email</label>
                <input class="input-field" type="email" v-model="credentials.email" placeholder="admin@example.com" @keyup.enter="login" />
            </div>
            <div style="margin-bottom: 24px;">
                <label class="input-label">Password</label>
                <input class="input-field" type="password" v-model="credentials.password" placeholder="••••••••" @keyup.enter="login" />
            </div>
            <button class="btn btn-primary" @click="login()" :disabled="loading" style="width: 100%;">
                @{{ loading ? 'Authenticating...' : 'Access Console' }}
            </button>
            <p v-if="error" style="color: var(--accent-red); font-size: 13px; margin-top: 16px; text-align: center; padding: 12px; background: rgba(248, 113, 113, 0.1); border-radius: 10px; border: 1px solid rgba(248, 113, 113, 0.3);">
                @{{ error }}
            </p>
        </div>
    </div>

    <!-- Dashboard -->
    <div v-else>
        <!-- Header -->
        <div class="header">
            <div class="headline">
                <div class="logo-area">
                    <div class="logo-icon">⚡</div>
                    <div>
                        <h1>AURA</h1>
                        <p class="subtitle">Control Center</p>
                    </div>
                </div>
                <div class="btn-group">
                    <div class="status-pulse">
                        <span class="pulse-dot" :class="connectionStatus ? 'online' : 'offline'"></span>
                        <span style="font-size: 12px; font-weight: 600; font-family: 'JetBrains Mono', monospace;" :style="{ color: connectionStatus ? 'var(--accent-green)' : 'var(--accent-red)' }">
                            @{{ connectionStatus ? 'ONLINE' : 'OFFLINE' }}
                        </span>
                    </div>
                    <span class="badge" :class="profile.role === 'admin' ? 'badge-pink' : 'badge-violet'" style="font-size: 12px;">
                        @{{ profile.name }}
                    </span>
                    <button class="btn btn-primary btn-sm" @click="refreshAll()" :disabled="loading">↻ Refresh</button>
                    <button class="btn btn-danger btn-sm" @click="logout()">Sign Out</button>
                </div>
            </div>
            <div class="last-update">Last sync · @{{ lastUpdateTime }}</div>
        </div>

        <!-- Syncing Bar -->
        <div class="syncing-bar" v-if="isSyncing">
            <div class="syncing-spinner"></div>
            <span>Processing changes...</span>
        </div>

        <!-- Sensor Cards -->
        <div class="sensor-cards-grid">
            <!-- ========== MOTION SENSOR CARD ========== -->
            <div class="sensor-card" :class="{ 'motion-active': motionActive }">
                <div class="card-accent-line"></div>
                <div class="card-inner">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="icon-circle" :class="motionActive ? 'motion-active' : 'inactive'">
                                @{{ motionActive ? '🏃' : '🧍' }}
                            </div>
                            <div>
                                <div class="card-title">Motion Sensor</div>
                                <div class="card-status" :style="{ color: motionActive ? 'var(--accent-red)' : 'var(--accent-green)' }">
                                    @{{ motionActive ? 'Activity Detected' : 'Area Clear' }}
                                </div>
                            </div>
                        </div>
                        <div class="sensor-value-badge" :class="motionActive ? 'badge-motion' : 'badge-inactive'">
                            @{{ sensors.motion.value !== null ? sensors.motion.value : '--' }}
                        </div>
                    </div>

                    <div class="card-divider"></div>

                    <!-- Buzzer Controls -->
                    <div class="controls-section">
                        <div class="controls-label">Actuator Controls</div>

                        <!-- Buzzer Toggle -->
                        <div class="control-row">
                            <div class="control-info">
                                <div class="control-icon-box" style="background: rgba(139, 92, 246, 0.15);">🔊</div>
                                <div>
                                    <div class="control-name">Buzzer Alarm</div>
                                    <div class="control-hint">
                                        Triggers on motion · @{{ getActuatorState('buzzer').state ? 'Enabled' : 'Disabled' }}
                                    </div>
                                </div>
                            </div>
                            <label class="toggle-switch" :class="{ loading: switching.buzzer }">
                                <input type="checkbox" :checked="getActuatorState('buzzer').state"
                                    @change="toggleActuator('buzzer', $event.target.checked)">
                                <span class="toggle-track"></span>
                            </label>
                        </div>

                        <!-- Duration -->
                        <div class="duration-label">Alarm Duration</div>
                        <div class="duration-row">
                            <button class="duration-btn" :class="{ active: buzzerDuration === 1 }"
                                @click="toggleActuator('buzzer_duration', 1)" :disabled="switching.buzzer_duration">2 sec</button>
                            <button class="duration-btn" :class="{ active: buzzerDuration === 2 }"
                                @click="toggleActuator('buzzer_duration', 2)" :disabled="switching.buzzer_duration">4 sec</button>
                            <button class="duration-btn" :class="{ active: buzzerDuration === 3 }"
                                @click="toggleActuator('buzzer_duration', 3)" :disabled="switching.buzzer_duration">6 sec</button>
                        </div>
                        <div class="duration-current">
                            Current: @{{ buzzerDuration === 1 ? '2 seconds' : (buzzerDuration === 2 ? '4 seconds' : (buzzerDuration === 3 ? '6 seconds' : 'Unknown')) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== LIGHT SENSOR CARD ========== -->
            <div class="sensor-card" :class="{ 'light-active': lightOn }">
                <div class="card-accent-line"></div>
                <div class="card-inner">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="icon-circle" :class="lightOn ? 'light-active' : (lightOff ? 'dark-active' : 'inactive')">
                                @{{ lightOn ? '☀️' : '🌙' }}
                            </div>
                            <div>
                                <div class="card-title">Light Sensor</div>
                                <div class="card-status" :style="{ color: lightOn ? 'var(--accent-amber)' : 'var(--accent-violet-light)' }">
                                    @{{ lightOn ? 'Illuminated' : (lightOff ? 'Darkness' : 'Reading...') }}
                                </div>
                            </div>
                        </div>
                        <div class="sensor-value-badge" :class="lightOn ? 'badge-light' : 'badge-dark'">
                            @{{ sensors.light.value !== null ? sensors.light.value : '--' }}
                        </div>
                    </div>

                    <div class="card-divider"></div>

                    <!-- LED Controls -->
                    <div class="controls-section">
                        <div class="controls-label">Lighting Controls</div>

                        <!-- Amber Light (Yellow LED) -->
                        <div class="control-row">
                            <div class="control-info">
                                <div class="control-icon-box" style="background: rgba(251, 191, 36, 0.15);">
                                    <span class="led-indicator" style="background: var(--accent-amber); color: var(--accent-amber);"></span>
                                </div>
                                <div>
                                    <div class="control-name">Amber Light</div>
                                    <div class="control-hint">
                                        Day indicator · @{{ getActuatorState('yellow_led').state ? 'Active' : 'Off' }}
                                    </div>
                                </div>
                            </div>
                            <label class="toggle-switch" :class="{ loading: switching.yellow_led }">
                                <input type="checkbox" :checked="getActuatorState('yellow_led').state"
                                    @change="toggleActuator('yellow_led', $event.target.checked)">
                                <span class="toggle-track"></span>
                            </label>
                        </div>

                        <!-- Crimson Light (Red LED) -->
                        <div class="control-row">
                            <div class="control-info">
                                <div class="control-icon-box" style="background: rgba(248, 113, 113, 0.15);">
                                    <span class="led-indicator" style="background: var(--accent-red); color: var(--accent-red);"></span>
                                </div>
                                <div>
                                    <div class="control-name">Crimson Light</div>
                                    <div class="control-hint">
                                        Night indicator · @{{ getActuatorState('red_led').state ? 'Active' : 'Off' }}
                                    </div>
                                </div>
                            </div>
                            <label class="toggle-switch" :class="{ loading: switching.red_led }">
                                <input type="checkbox" :checked="getActuatorState('red_led').state"
                                    @change="toggleActuator('red_led', $event.target.checked)">
                                <span class="toggle-track"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables -->
        <div class="section">
            <div class="tab-nav">
                <button class="tab-btn" :class="{ active: activeTab === 'telemetry' }" @click="activeTab = 'telemetry'">📊 Telemetry</button>
                <button class="tab-btn" :class="{ active: activeTab === 'activities' }" @click="activeTab = 'activities'">📋 Activity Log</button>
            </div>

            <div class="tab-panel" :class="{ active: activeTab === 'telemetry' }">
                <div class="text-muted mb-4" style="font-size: 12px;">Showing @{{ telemetry.length }} entries</div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr><th>ID</th><th>Sensor</th><th>Value</th><th>Unit</th><th>Timestamp</th></tr>
                        </thead>
                        <tbody>
                            <tr v-for="entry in telemetry" :key="entry.id">
                                <td><span class="badge badge-violet">#@{{ entry.id }}</span></td>
                                <td><span class="badge" :class="entry.sensor_type === 'motion' ? 'badge-red' : 'badge-amber'">@{{ entry.sensor_type === 'light' ? '☀️ Light' : '🏃 Motion' }}</span></td>
                                <td style="font-weight: 600;">@{{ entry.value }}</td>
                                <td class="text-muted">@{{ entry.unit || 'N/A' }}</td>
                                <td class="text-muted" style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">@{{ new Date(entry.created_at).toLocaleString() }}</td>
                            </tr>
                            <tr v-if="telemetry.length === 0"><td colspan="5" class="text-center text-muted" style="padding: 32px;">No telemetry data</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-panel" :class="{ active: activeTab === 'activities' }">
                <div class="text-muted mb-4" style="font-size: 12px;">Showing @{{ activities.length }} entries</div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr><th>Time</th><th>User</th><th>Actuator</th><th>Action</th><th>State</th></tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in activities" :key="item.id">
                                <td class="text-muted" style="font-family: 'JetBrains Mono', monospace; font-size: 11px;">@{{ new Date(item.created_at).toLocaleString() }}</td>
                                <td>@{{ item.user || 'system' }}</td>
                                <td><span class="badge badge-violet">@{{ item.actuator_name }}</span></td>
                                <td><span class="badge" :class="item.action === 'enabled' ? 'badge-green' : 'badge-red'">@{{ item.action }}</span></td>
                                <td style="font-weight: 600;">@{{ item.state ?? 'n/a' }}</td>
                            </tr>
                            <tr v-if="activities.length === 0"><td colspan="5" class="text-center text-muted" style="padding: 32px;">No activity logged</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-bar">
            <div class="footer-row">
                <span class="footer-dot"></span>
                <span>Auto-refresh active · 3s interval</span>
            </div>
            <div class="footer-row">
                <span class="footer-dot" :style="{ backgroundColor: isSyncing ? 'var(--accent-amber)' : 'var(--accent-green)' }"></span>
                <span>@{{ isSyncing ? 'Lock engaged · Syncing' : 'Ready for commands' }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            token: localStorage.getItem('iot_admin_token') || null,
            profile: { name: '', email: '', role: '' },
            credentials: { email: 'admin@example.com', password: 'password' },
            loading: false,
            error: null,
            isSyncing: false,

            connectionStatus: false,
            lastPingHuman: 'Never',
            lastUpdateTime: '',
            sensors: {
                light: { value: null, unit: null, updated_at: null },
                motion: { value: null, unit: null, updated_at: null },
            },
            actuators: [],
            telemetry: [],
            activities: [],
            todayActivities: 0,

            activeTab: 'telemetry',
            switching: { yellow_led: false, red_led: false, buzzer: false, buzzer_duration: false },

            inactivityTimer: null,
            showTimeoutWarning: false,
            timeoutSeconds: 60,
            INACTIVITY_LIMIT: 300,
            WARNING_BEFORE: 60,
            lastActivity: Date.now(),

            pollTimer: null,
            
            // NEW: Motion display timer
            motionDisplayTimer: null,
            // Override motionActive to use local timer instead of server value
            localMotionActive: false,
        };
    },
    computed: {
        motionActive() {
            // Use local motion state that auto-clears after buzzer duration
            return this.localMotionActive || this.sensors.motion.value === 1;
        },
        lightOn() {
            return this.sensors.light.value === 1;
        },
        lightOff() {
            return this.sensors.light.value === 0;
        },
        buzzerDuration() {
            const buzzer = this.actuators.find(a => a.actuator_name === 'buzzer_duration');
            return buzzer ? Number(buzzer.state) : 2;
        },
        // Get buzzer duration in milliseconds
        buzzerDurationMs() {
            switch(this.buzzerDuration) {
                case 1: return 2000;  // 2 seconds
                case 2: return 4000;  // 4 seconds
                case 3: return 6000;  // 6 seconds
                default: return 4000;
            }
        },
        // Get buzzer duration display text
        buzzerDurationText() {
            switch(this.buzzerDuration) {
                case 1: return '2 seconds';
                case 2: return '4 seconds';
                case 3: return '6 seconds';
                default: return 'Unknown';
            }
        }
    },
    watch: {
        // Watch for motion sensor value changes
        'sensors.motion.value': function(newVal, oldVal) {
            if (newVal === 1 && oldVal !== 1) {
                // Motion detected - show immediately and set auto-clear timer
                this.triggerMotionDisplay();
            }
        }
    },
    mounted() {
        if (this.token) {
            this.loadProfile();
            this.startPolling();
            this.resetInactivityTimer();
            this.setupActivityListeners();
        }
    },
    beforeUnmount() {
        this.stopPolling();
        this.clearInactivityTimer();
        this.clearMotionTimer();
    },
    methods: {
        // NEW: Trigger motion display based on buzzer duration
        triggerMotionDisplay() {
            // Clear any existing timer
            this.clearMotionTimer();
            
            // Show motion immediately
            this.localMotionActive = true;
            
            // Auto-clear after buzzer duration
            this.motionDisplayTimer = setTimeout(() => {
                this.localMotionActive = false;
                console.log('Motion display auto-cleared after ' + this.buzzerDurationText);
            }, this.buzzerDurationMs);
            
            console.log('Motion detected! Displaying for ' + this.buzzerDurationText);
        },
        
        // NEW: Clear motion timer
        clearMotionTimer() {
            if (this.motionDisplayTimer) {
                clearTimeout(this.motionDisplayTimer);
                this.motionDisplayTimer = null;
            }
        },
        
        async login() {
            this.error = null;
            this.loading = true;
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.credentials),
                });
                if (!response.ok) {
                    const data = await response.json();
                    this.error = data.message || 'Login failed';
                    return;
                }
                const data = await response.json();
                this.token = data.token;
                localStorage.setItem('iot_admin_token', this.token);
                this.profile = data.user;
                await this.loadDashboard(true);
                this.startPolling();
                this.resetInactivityTimer();
                this.setupActivityListeners();
            } catch (err) {
                this.error = 'Unable to log in. Check server URL.';
            } finally {
                this.loading = false;
            }
        },
        async loadProfile() {
            try {
                const response = await fetch('/api/profile', {
                    headers: { Authorization: `Bearer ${this.token}` },
                });
                if (response.ok) {
                    this.profile = await response.json();
                    await this.loadDashboard();
                } else {
                    this.logout();
                }
            } catch (err) {
                this.logout();
            }
        },
        async loadDashboard(full = true) {
            if (!this.token) return;
            try {
                const endpoint = full ? '/api/dashboard' : '/api/dashboard/poll';
                const response = await fetch(endpoint, {
                    headers: { Authorization: `Bearer ${this.token}` },
                });
                if (response.ok) {
                    const data = await response.json();
                    this.connectionStatus = data.connection.connected;
                    this.lastPingHuman = data.connection.last_ping_human;
                    
                    // Store previous motion value to detect changes
                    const previousMotion = this.sensors.motion.value;
                    
                    this.sensors = data.sensors;
                    this.actuators = data.actuators || [];
                    this.lastUpdateTime = new Date().toLocaleTimeString();
                    
                    // Ensure buzzer_duration exists in actuators array
                    if (data.buzzer_duration !== undefined) {
                        const existing = this.actuators.findIndex(a => a.actuator_name === 'buzzer_duration');
                        if (existing >= 0) {
                            this.actuators[existing].state = data.buzzer_duration;
                        } else {
                            this.actuators.push({
                                actuator_name: 'buzzer_duration',
                                state: data.buzzer_duration,
                                controlled_by: 'system'
                            });
                        }
                    }
                    
                    // Check if motion just became active
                    if (this.sensors.motion.value === 1 && previousMotion !== 1) {
                        this.triggerMotionDisplay();
                    }
                    
                    if (full) {
                        this.telemetry = data.telemetry || [];
                        this.activities = data.activities || [];
                        this.todayActivities = this.activities.filter(a => {
                            return new Date(a.created_at).toDateString() === new Date().toDateString();
                        }).length;
                    }
                } else if (response.status === 401) {
                    this.logout();
                }
            } catch (err) {
                console.error('Dashboard load error:', err);
            }
        },
        async refreshAll() {
            this.loading = true;
            await this.loadDashboard(true);
            this.loading = false;
        },
        getActuatorState(name) {
            const found = this.actuators.find(a => a.actuator_name === name);
            return found || { state: false, controlled_by: 'system' };
        },
        async toggleActuator(name, state) {
            this.switching[name] = true;
            this.isSyncing = true;
            this.recordActivity();
            try {
                const isDuration = name === 'buzzer_duration';
                const endpoint = isDuration ? '/api/actuators/duration' : '/api/actuators/web-control';
                const body = isDuration ? { duration: state } : { actuator: name, state };
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${this.token}`,
                    },
                    body: JSON.stringify(body),
                });
                const data = await response.json();
                if (data.success) {
                    const label = isDuration 
                        ? `Duration: ${state === 1 ? '2s' : state === 2 ? '4s' : '6s'}` 
                        : `${name.replace('_', ' ').toUpperCase()} ${state ? 'ON' : 'OFF'}`;
                    this.showToast(label, 'success');
                    
                    // Update local state immediately for duration
                    if (isDuration) {
                        const existing = this.actuators.findIndex(a => a.actuator_name === 'buzzer_duration');
                        if (existing >= 0) {
                            this.actuators[existing].state = state;
                        } else {
                            this.actuators.push({
                                actuator_name: 'buzzer_duration',
                                state: state,
                                controlled_by: 'user'
                            });
                        }
                    }
                    
                    await this.loadDashboard(false);
                } else {
                    this.showToast(`Failed to update ${name}`, 'error');
                }
            } catch (err) {
                this.showToast('Network error', 'error');
            } finally {
                this.switching[name] = false;
                setTimeout(() => { this.isSyncing = false; }, 500);
            }
        },
        startPolling() {
            this.pollTimer = setInterval(() => {
                if (this.token) this.loadDashboard(false);
            }, 3000);
        },
        stopPolling() {
            if (this.pollTimer) {
                clearInterval(this.pollTimer);
                this.pollTimer = null;
            }
        },
        setupActivityListeners() {
            ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, () => this.recordActivity());
            });
        },
        recordActivity() {
            this.lastActivity = Date.now();
            this.showTimeoutWarning = false;
        },
        resetInactivityTimer() {
            this.clearInactivityTimer();
            this.inactivityTimer = setInterval(() => {
                const elapsed = (Date.now() - this.lastActivity) / 1000;
                const remaining = this.INACTIVITY_LIMIT - elapsed;
                if (remaining <= this.WARNING_BEFORE && remaining > 0) {
                    this.showTimeoutWarning = true;
                    this.timeoutSeconds = Math.round(remaining);
                }
                if (elapsed >= this.INACTIVITY_LIMIT) {
                    this.showTimeoutWarning = false;
                    this.showToast('Session expired', 'error');
                    this.logout();
                }
            }, 1000);
        },
        clearInactivityTimer() {
            if (this.inactivityTimer) {
                clearInterval(this.inactivityTimer);
                this.inactivityTimer = null;
            }
            this.showTimeoutWarning = false;
        },
        toastMessage: '',
        toastClass: '',
        toastTimer: null,
        showToast(message, type = 'success') {
            this.toastMessage = message;
            this.toastClass = 'show ' + type;
            if (this.toastTimer) clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => {
                this.toastMessage = '';
                this.toastClass = '';
            }, 3000);
        },
        formatTime(dateStr) {
            if (!dateStr) return 'N/A';
            const d = new Date(dateStr);
            const now = new Date();
            const diff = (now - d) / 1000;
            if (diff < 10) return 'Just now';
            if (diff < 60) return Math.round(diff) + 's ago';
            if (diff < 3600) return Math.round(diff / 60) + 'm ago';
            return d.toLocaleString();
        },
        logout() {
            this.stopPolling();
            this.clearInactivityTimer();
            this.clearMotionTimer(); // Clear motion timer on logout
            this.token = null;
            this.profile = { name: '', email: '', role: '' };
            this.actuators = [];
            this.telemetry = [];
            this.activities = [];
            this.connectionStatus = false;
            this.localMotionActive = false;
            localStorage.removeItem('iot_admin_token');
        },
    },
}).mount('#app');
</script>
</body>
</html>