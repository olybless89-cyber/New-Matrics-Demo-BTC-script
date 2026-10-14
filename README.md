# TrustCrypto Platform

A crypto investment, trading and debit card issuance platform built with PHP 8.2 and MySQL / SQLite.

## Features
- **Crypto Debit Cards**: Instant Virtual Cards (Free), Titanium Metal Cards ($25), VIP Black Diamond Cards ($100).
- **Interactive 3D Flip Card**: EMV chip, contactless payments, 1-click number copy, real-time CVV & PIN reveal.
- **Card Security Controls**: One-click freeze/unfreeze, daily & monthly spending limits, online and international toggles.
- **Card Balance & POS Simulator**: Load from main wallet, withdraw back to balance, and simulate real merchant purchases (Amazon, Netflix, Apple, Uber) with instant crypto cashbacks!
- **Special Offers & Rewards Hub**: Active cashbacks, APY yield boosters, and promo code redemption (`WELCOME50`, `CRYPTOVIP`, `STREAM10`, `WEEKEND15`, `BOOST45`).
- **Investment Proposals**: Hourly & daily high-yield investment packages with automated return calculations.
- **Deposits & Withdrawals**: Multi-currency crypto funding and withdrawal requests with admin approval.
- **KYC Verification & Support Tickets**: Identity document upload, verification workflow, and internal support desk.
- **Comprehensive Admin Panel**: Full management of users, deposits, withdrawals, debit card issuance, investments, and KYC reviews.

---

## Seamless Auto-Deploy on Railway

This repository is pre-configured for 1-click zero-config deployment on [Railway](https://railway.app):

1. Go to **Railway.app** -> **New Project** -> **Deploy from GitHub repo**.
2. Select `olybless89-cyber/New-Matrics-Demo-BTC-script`.
3. Railway will automatically detect the `Dockerfile` and `railway.json`.
4. (Optional) In your Railway project, click **+ New** -> **Database** -> **Add MySQL**. The application will automatically detect Railway's MySQL environment variables (`MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, etc.) and connect.
   * *Note: If no MySQL database is attached, the app will automatically run using SQLite without any configuration required!*
5. Click **Generate Domain** under service settings to get your live public URL!

### Default Admin Credentials
- **Email:** `admin@trustcrypto.com`
- **Password:** `admin123`
- **Admin Panel URL:** `/admin`

### Default User Credentials
- **Email:** `coder@gmail.com`
- **Password:** `12345678`
