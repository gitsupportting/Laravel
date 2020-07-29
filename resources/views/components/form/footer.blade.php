<div class="table-control">
    <div class="table-control__inner">
        <div class="button-left">
            <a class="btn btn-primary btn-square" onclick="javascript:window.location=document.referrer;" href="#">Back</a>
        </div>
        <div class="button_right">
            @if($entity->id)
                <a href="javascript:;" class="btn btn-primary btn-square" onclick="if(confirm('{{ $deleteMessage ?? 'Are you sure you want to delete this item?'}}')) $('#{{ $entity->getTable() }}-delete-form').submit();">Delete</a>
            @endif
            <a href="{{ route('home', ['anchor' => $anchor ?? '']) }}" class="btn btn-primary btn-square">Cancel</a>
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
