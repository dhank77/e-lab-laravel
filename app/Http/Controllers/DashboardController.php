<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lab;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', [
                'stats' => [
                    'labs' => Lab::count(),
                    'items' => Item::sum('stock'),
                    'users' => User::where('role', 'user')->count(),
                    'pending' => Loan::where('status', 'pending')->count(),
                    'active' => Loan::where('status', 'approved')->count(),
                ],
                'latestLoans' => Loan::with(['user', 'item.lab'])->latest()->take(8)->get(),
            ]);
        }

        return view('dashboard.user', [
            'stats' => [
                'pending' => $user->loans()->where('status', 'pending')->count(),
                'active' => $user->loans()->where('status', 'approved')->count(),
                'returned' => $user->loans()->where('status', 'returned')->count(),
            ],
            'latestLoans' => $user->loans()->with('item.lab')->latest()->take(5)->get(),
            'labs' => Lab::withCount('items')->get(),
        ]);
    }
}
