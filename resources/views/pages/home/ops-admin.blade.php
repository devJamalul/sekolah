@extends('layout.master-page')

@section('title', $title)

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{ str(auth()->user()->getRoleNames()[0])->title }}</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary font-weight-bold text-light">
                    Schools Statistics
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" valign="middle" class="text-center align-middle">School</th>
                                <th colspan="10" class="text-center align-middle">Attributes</th>
                            </tr>

                            <tr>
                                <th class="text-center">Users</th>
                                <th class="text-center">Staff</th>
                                <th class="text-center">A. Year</th>
                                <th class="text-center">Grades</th>
                                <th class="text-center">Classrooms</th>
                                <th class="text-center">Tuit. Types</th>
                                <th class="text-center">Tuitions</th>
                                <th class="text-center">Wallets</th>
                                <th class="text-center">Pay. Types</th>
                                <th class="text-center">Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schools as $school)
                                <tr>
                                    <td scope="row">{{ $school->school_name }}</td>
                                    <td class="text-center">
                                        @if ($school->users()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->staff()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->academic_years()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->grades()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->classrooms()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->tuition_types()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    </td>
                                    <td class="text-center">
                                        @if ($school->tuitions()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->wallets()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->payment_types()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($school->students()->withoutGlobalScopes()->count() >= 1)
                                            ✅
                                        @else
                                            ❌
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted">
                    Footer
                </div>
            </div>
        </div>
    </div>
@endsection
