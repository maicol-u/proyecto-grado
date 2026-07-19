<?php

namespace App\Http\Controllers\Custumer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustumerDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $crops = $request->user()
            ->crops()
            ->select('crops.id', 'crops.name', 'crops.location')
            ->withCount('sensors')
            ->get();

        return Inertia::render('Dashboard', [
            'crops' => $crops,
        ]);
    }

    public function showCropCustumer(Request $request, Crop $invernadero): Response
    {
        abort_unless(
            $request->user()->crops()->whereKey($invernadero->id)->exists(),
            403,
            'No tienes acceso a este invernadero.'
        );

        $crop = $invernadero
            ->loadCount('sensors')
            ->load([
                'sensors:id,crop_id,name,model,unit,status',
            ]);

        return Inertia::render('custumer/CropDashboard', [
            'crop' => $crop,
        ]);
    }
}
