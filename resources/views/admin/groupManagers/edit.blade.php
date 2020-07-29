@extends('layouts.internal')
@section('title', 'Edit group')
@section('content')
    <div class="content-wrapper add-ministrator" data-simplebar="">
        <section class="section-manager container">
            <div class="section-manager__content ">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>
                            <form class="form form-admin" method="post" enctype="multipart/form-data"
                                  action="{{route('groups.managers.update', [$group, $entity])}}">
                                @csrf
                                @method('put')
                                @include('admin.groupManagers._form')
                            </form>
                            <form id="users-delete-form" method="post"
                                  action="{{route('groups.managers.destroy', [$group, $entity])}}">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
