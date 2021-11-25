<div class="row d-flex flex-wrap">    
@foreach($data as $row)
    <wiki-post-card row_id="{{ $row->id }}"></wiki-post-card>
@endforeach
</div>