<convert-lead
	:lead='@json($content)'
	:use_tags='@json($resource->useTags())'
	resource_id="{{ $resource->id }}"
>
</convert-lead> 