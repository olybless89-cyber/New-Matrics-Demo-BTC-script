<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\DepositController;
use App\Controllers\WithdrawController;
use App\Controllers\InvestmentController;
use App\Controllers\CardController;
use App\Controllers\AdminController;
use App\Controllers\KycController;
use App\Controllers\ReferralController;
use App\Controllers\SupportController;
use App\Controllers\ExchangeController;
use App\Controllers\HistoryController;

$router->get('/', [HomeController::class, 'index']);
$router->get('about', [HomeController::class, 'about']);
$router->get('investments', [HomeController::class, 'investments']);
$router->get('faq', [HomeController::class, 'faq']);
$router->get('contacts', [HomeController::class, 'contacts']);
$router->post('contacts', [HomeController::class, 'contacts']);
$router->get('statistic', [HomeController::class, 'statistic']);
$router->get('terms', [HomeController::class, 'terms']);

$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'login']);
$router->get('register', [AuthController::class, 'register']);
$router->post('register', [AuthController::class, 'register']);
$router->get('logout', [AuthController::class, 'logout']);
$router->get('forgot-password', [AuthController::class, 'forgot']);
$router->post('forgot-password', [AuthController::class, 'forgot']);

$router->get('dashboard', [DashboardController::class, 'index']);
$router->get('dashboard/transactions', [DashboardController::class, 'transactions']);
$router->get('dashboard/profile', [DashboardController::class, 'profile']);
$router->post('dashboard/profile', [DashboardController::class, 'updateProfile']);
$router->get('dashboard/kyc', [KycController::class, 'index']);
$router->post('dashboard/kyc', [KycController::class, 'store']);
$router->get('dashboard/referrals', [ReferralController::class, 'index']);
$router->get('dashboard/support', [SupportController::class, 'index']);
$router->get('dashboard/support/create', [SupportController::class, 'create']);
$router->post('dashboard/support/create', [SupportController::class, 'store']);
$router->get('dashboard/support/{id}', [SupportController::class, 'show']);
$router->post('dashboard/support/{id}/reply', [SupportController::class, 'reply']);
$router->post('dashboard/support/{id}/close', [SupportController::class, 'close']);
$router->get('dashboard/exchange', [ExchangeController::class, 'index']);
$router->get('dashboard/trading-history', [HistoryController::class, 'trading']);
$router->get('dashboard/account-history', [HistoryController::class, 'account']);

$router->get('deposit', [DepositController::class, 'index']);
$router->post('deposit', [DepositController::class, 'store']);
$router->get('dashboard/deposits', [DepositController::class, 'index']);
$router->post('dashboard/deposits', [DepositController::class, 'store']);

$router->get('withdraw', [WithdrawController::class, 'index']);
$router->post('withdraw', [WithdrawController::class, 'store']);
$router->get('dashboard/withdrawals', [WithdrawController::class, 'index']);
$router->post('dashboard/withdrawals', [WithdrawController::class, 'store']);

$router->get('invest', [InvestmentController::class, 'index']);
$router->post('invest', [InvestmentController::class, 'store']);
$router->get('dashboard/mpack', [InvestmentController::class, 'index']);
$router->post('dashboard/mpack', [InvestmentController::class, 'store']);

$router->get('cards', [CardController::class, 'index']);
$router->post('cards/apply', [CardController::class, 'apply']);
$router->post('cards/fund', [CardController::class, 'fund']);
$router->post('cards/{id}/freeze', [CardController::class, 'freeze']);
$router->post('cards/{id}/unfreeze', [CardController::class, 'unfreeze']);
$router->post('cards/{id}/withdraw', [CardController::class, 'withdrawToWallet']);
$router->post('cards/{id}/limits', [CardController::class, 'updateLimits']);
$router->post('cards/{id}/simulate', [CardController::class, 'simulateSpend']);

$router->get('dashboard/cards', [CardController::class, 'index']);
$router->post('dashboard/cards/apply', [CardController::class, 'apply']);
$router->post('dashboard/cards/fund', [CardController::class, 'fund']);
$router->post('dashboard/cards/{id}/freeze', [CardController::class, 'freeze']);
$router->post('dashboard/cards/{id}/unfreeze', [CardController::class, 'unfreeze']);
$router->post('dashboard/cards/{id}/withdraw', [CardController::class, 'withdrawToWallet']);
$router->post('dashboard/cards/{id}/limits', [CardController::class, 'updateLimits']);
$router->post('dashboard/cards/{id}/simulate', [CardController::class, 'simulateSpend']);

$router->get('offers', [CardController::class, 'offers']);
$router->get('dashboard/offers', [CardController::class, 'offers']);
$router->post('dashboard/offers/claim', [CardController::class, 'claimOffer']);
$router->post('dashboard/offers/redeem', [CardController::class, 'redeemCode']);

$router->get('dashboard/accounthistory', [HistoryController::class, 'account']);
$router->get('dashboard/tradinghistory', [HistoryController::class, 'trading']);
$router->get('dashboard/asset-balance', [ExchangeController::class, 'index']);
$router->get('dashboard/referuser', [ReferralController::class, 'index']);
$router->get('dashboard/support', [SupportController::class, 'index']);

$router->get('admin', [AdminController::class, 'index']);
$router->get('admin/users', [AdminController::class, 'users']);
$router->get('admin/deposits', [AdminController::class, 'deposits']);
$router->post('admin/deposits/{id}/approve', [AdminController::class, 'approveDeposit']);
$router->post('admin/deposits/{id}/reject', [AdminController::class, 'rejectDeposit']);
$router->get('admin/withdrawals', [AdminController::class, 'withdrawals']);
$router->post('admin/withdrawals/{id}/approve', [AdminController::class, 'approveWithdrawal']);
$router->post('admin/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal']);
$router->get('admin/cards', [AdminController::class, 'cards']);
$router->post('admin/cards/issue', [AdminController::class, 'issueCard']);
$router->post('admin/cards/{id}/block', [AdminController::class, 'blockCard']);
$router->post('admin/cards/{id}/activate', [AdminController::class, 'activateCard']);
$router->get('admin/investments', [AdminController::class, 'investments']);
$router->post('admin/investments', [AdminController::class, 'storeInvestment']);
$router->get('admin/messages', [AdminController::class, 'messages']);
$router->get('admin/kyc', [AdminController::class, 'kyc']);
$router->post('admin/kyc/{id}/approve', [AdminController::class, 'approveKyc']);
$router->post('admin/kyc/{id}/reject', [AdminController::class, 'rejectKyc']);
$router->get('admin/support', [AdminController::class, 'support']);
$router->get('admin/support/{id}', [AdminController::class, 'supportShow']);
$router->post('admin/support/{id}/reply', [AdminController::class, 'supportReply']);
$router->post('admin/support/{id}/close', [AdminController::class, 'supportClose']);
