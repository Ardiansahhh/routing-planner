<?php

namespace App\Http\Controllers\WH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RayonController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $toko = DB::connection('CSAREPORT')->select("SELECT A.fc_branch, A.kode_rayon, A.name, A.tanggal FROM [CSAREPORT].[dbo].[t_rayon] A WITH (NOLOCK) WHERE fc_branch = '$user->fc_branch'");
        return view('rayon/index', ['data' => $toko]);
    }

    public function input()
    {
        return view('rayon/input');
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        if (isset($_POST['create_rayon'])) {
            $user = Auth::user();
            $today = date('d-m-Y H:i:s');
            $kode_rayon = strtoupper($request->kode_rayon);
            $cek_kode = DB::connection('CSAREPORT')->select("SELECT kode_rayon FROM [CSAREPORT].[dbo].[t_rayon] WHERE fc_branch = '$user->fc_branch' AND kode_rayon = '$kode_rayon'");
            if (!$cek_kode) {
                $data = [
                    'fc_branch'  => $user->fc_branch,
                    'kode_rayon' => $kode_rayon,
                    'name'       => $user->name,
                    'tanggal'    => $today
                ];
                DB::connection('CSAREPORT')->table('t_rayon')->insert($data);
                return redirect('/rayon')->with('success', 'Sukses ditambahkan');
            }
            return redirect()->back()->with('session', 'Kode Sudah digunakan');
        }
    }

    public function setting($kode_rayon)
    {
        $user = Auth::user();
        $cek_toko = DB::connection('CSAREPORT')->select("SELECT A.FC_BRANCH, A.FC_CUSTCODE, FV_CUSTNAME, FV_CUSTADD1, FV_CUSTCITY
                                                        FROM [CSAREPORT].[dbo].[t_temporary_customer] A WITH (NOLOCK) WHERE A.FC_BRANCH = '$user->fc_branch'");
        if ($cek_toko) {
            return view('rayon/setting', [
                'data' => $cek_toko,
                'rayon' => $kode_rayon,
                'isContent' => true
            ]);
        } else {
            return view('rayon/setting', [
                'isContent' => false,
                'rayon' => $kode_rayon,
            ]);
        }
    }

    public function load_rayon(Request $request)
    {
        $user = Auth::user();
        $check_temporary = DB::connection('CSAREPORT')->select("SELECT FC_BRANCH FROM [CSAREPORT].[dbo].[t_temporary_customer] 
                                                                WITH (NOLOCK) WHERE FC_BRANCH = '$user->fc_branch'");
        if ($check_temporary) {
            DB::connection('CSAREPORT')->delete("DELETE FROM [CSAREPORT].[dbo].[t_temporary_customer] WHERE FC_BRANCH = '$user->fc_branch'");
        }
        if (isset($_POST['load_rayon'])) {
            $true = true;
            if ($true) {
                $toko = DB::connection('other3')->select("SELECT FC_BRANCH, FC_CUSTCODE, FV_CUSTNAME, FV_CUSTADD1, FV_CUSTCITY
                                                  FROM [d_master].[dbo].[t_customer] WITH (NOLOCK) WHERE 
                                                  FC_BRANCH = '$user->fc_branch' AND FC_CUSTTYPE = 'PR' AND FC_CUSTHOLD = 'NO' ORDER BY FV_CUSTCITY");
                if ($toko) {
                    $guard = 0;
                    $will_insert = [];
                    foreach ($toko as $t) {
                        array_push($will_insert, [
                            'FC_BRANCH'   => $t->FC_BRANCH,
                            'FC_CUSTCODE' => $t->FC_CUSTCODE,
                            'FV_CUSTNAME' => $t->FV_CUSTNAME,
                            'FV_CUSTADD1' => $t->FV_CUSTADD1,
                            'FV_CUSTCITY' => $t->FV_CUSTCITY
                        ]);
                        if ($guard == 80) {
                            DB::connection('CSAREPORT')->table('t_temporary_customer')->insert($will_insert);
                            $guard = 0;
                            $will_insert = [];
                        }
                        $guard += 1;
                    }
                    if ($guard > 0) {
                        DB::connection('CSAREPORT')->table('t_temporary_customer')->insert($will_insert);
                        $guard = 0;
                        $will_insert = [];
                        return redirect('/setting-rayon/' . $request->kode_rayon)->with('success', 'Data Successfully');
                    }
                }
            }
        }
        return redirect('/rayon');
    }
}
