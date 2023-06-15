<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UsersDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = User::with('staff')->role([User::ROLE_ADMIN_YAYASAN, User::ROLE_ADMIN_SEKOLAH, User::ROLE_KEPALA_SEKOLAH, User::ROLE_TATA_USAHA, User::ROLE_BENDAHARA])->where('school_id', session('school_id'))->latest();
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $data = [
                    'edit_url'     => route('users.edit', ['user' => $row->id]),
                    'delete_url'   => route('users.destroy', ['user' => $row->id]),
                    'redirect_url' => route('users.index'),
                    'resource'     => 'users',
                    'custom_links' => []
                ];

                array_push($data['custom_links'], ['label' => 'Ubah Password', 'url' => route('reset-user-password.edit', ['user' => $row->id]), 'name' => 'reset-user-password.edit']);

                return view('components.datatable-action', $data);
            })
            ->editColumn('name', function ($row) {
                return "<a href='" . route('users.show', $row->getKey()) . "' title='Detail' alt='Detail'>$row->name</a>";
            })
            ->addColumn('jabatan', function ($row) {
                return str($row->getRoleNames()[0])->title();
            })
            ->rawColumns(['name', 'action'])
            ->toJson();
    }
}
