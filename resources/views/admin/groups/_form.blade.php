<div class="form-body">
    <div class="row">
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label class="form-lable">Group Name</label>
                <input type="text" name="name" class="form-control" placeholder="Group Name"
                       value="{{ old('name', $entity->name) }}">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label class="form-lable">Organisations</label>
                <div class="organisations-group">
                    <div class="organisations-card">
                        <div class="organisations-card__inner" data-simplebar="">
                            <ul class="list-unstyled organisations-list add-organisation-list" data-target="#add_organisation">
                                @foreach($organizations as $organization)
                                    @if(!$entity->organizations->contains($organization))
                                        <li>
                                            <a href="javascript:;"> {{ $organization->name }}</a>
                                            <input type="hidden" name="organizations[]" value="{{ $organization->id }}" disabled/>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="organisations-button">
                        <button type="button" class="btn btn-primary" id="add_organisation">Add <i class="fas fa-angle-right"></i></button>
                        <button type="button" class="btn btn-primary" id="remove_organisation"><i class="fas fa-angle-left"></i> Remove</button>
                    </div>
                    <div class="organisations-card">
                        <div class="organisations-card__inner" data-simplebar="">
                            <ul class="list-unstyled organisations-list remove-organisation-list" data-target="#remove_organisation">
                                @foreach($entity->organizations as $organization)
                                    <li>
                                        <a href="javascript:;"> {{ $organization->name }}</a>
                                        <input type="hidden" name="organizations[]" value="{{ $organization->id }}"/>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="group-manager-table">
    @if($entity->exists)
        <div class="table-control--top">
            <div class="table-control__inner manager pb-0">
                <a href="{{ route('groups.managers.create', $entity) }}" class="btn btn-primary btn-square">Add Group Manager</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                @foreach($managers as $manager)
                    <tr>
                        <td> {{ $manager->name }} </td>
                        <td> {{ $manager->email }} </td>
                        <td> {{ $manager->phone }}</td>
                        <td><a href="{{ route('groups.managers.edit', [$entity, $manager]) }}" class="btn btn-primary" style="color: #042759">Edit</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @component('components.form.footer')
        @slot('entity', $entity)
    @endcomponent
</div>

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.organisations-list li a', function(){
                $('.organisations-list li').removeClass('selected');
                $(this).parent('li').addClass('selected');
            });

            $(document).on('click', '#add_organisation', function(){
                if ($('.add-organisation-list li').hasClass('selected')) {
                    var selected = $('[data-target="#add_organisation"] li.selected');
                    selected.find('input').attr('disabled', false);
                    var selected__text = selected.html();
                    $('.remove-organisation-list').append('<li>' + selected__text + '</li>');
                    selected.remove();
                }
            });
            $(document).on('click', '#remove_organisation', function(){
                if ($('.remove-organisation-list li').hasClass('selected')) {
                    var selected = $('[data-target="#remove_organisation"] li.selected');
                    selected.find('input').attr('disabled', true);
                    var selected__text = selected.html();
                    $('.add-organisation-list').append('<li>' + selected__text + '</li>');
                    selected.remove();
                }
            });
        })
    </script>
@endsection
