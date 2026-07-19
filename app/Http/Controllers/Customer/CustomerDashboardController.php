<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerDashboardController extends Controller
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

    public function showCropCustomer(Request $request, Crop $invernadero): Response
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

        return Inertia::render('customer/CropDashboard', [
            'crop' => $crop,
        ]);
    }
}
