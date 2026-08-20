<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PakasirWebhookController extends Controller
{
    /**
     * Handle incoming Pakasir Payment Gateway webhook notifications.
     */
    public function handle(Request $request)
    {
        Log::info('Pakasir Webhook Event Received:', $request->all());

        $status = strtolower($request->input('status') ?? '');
        $orderId = $request->input('order_id') ?? '';
        $project = $request->input('project') ?? '';

        // Check for success status (Pakasir official status: 'completed')
        if (in_array($status, ['completed', 'success', 'paid', 'settlement'])) {
            $tenantId = $request->input('tenant_id');
            $themeSlug = $request->input('theme_slug');

            // Smart theme slug matching from database if theme_slug isn't passed directly
            if (! $themeSlug && ! empty($orderId)) {
                $allSlugs = Theme::pluck('slug')->toArray();
                foreach ($allSlugs as $s) {
                    if (str_contains($orderId, $s)) {
                        $themeSlug = $s;
                        break;
                    }
                }
            }

            if (! $tenantId && str_contains($orderId, 'PAKASIR-THM-')) {
                $parts = explode('-', str_replace('PAKASIR-THM-', '', $orderId));
                $tenantId = $parts[0] ?? 1;
            }

            if ($tenantId && $themeSlug) {
                $tenant = Tenant::find($tenantId);
                if ($tenant) {
                    $purchased = is_array($tenant->purchased_themes) ? $tenant->purchased_themes : (json_decode($tenant->purchased_themes ?? '[]', true) ?: []);
                    if (! in_array($themeSlug, $purchased)) {
                        $purchased[] = $themeSlug;
                    }
                    $tenant->update([
                        'purchased_themes' => array_values(array_unique($purchased)),
                        'theme' => $themeSlug,
                    ]);
                    Log::info("Pakasir Webhook: Theme '{$themeSlug}' successfully unlocked for Tenant #{$tenant->id}");
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pakasir webhook notification processed successfully.',
            ], 200);
        }

        return response()->json([
            'status' => 'ignored',
            'message' => 'Transaction status is not completed.',
        ], 200);
    }
}
