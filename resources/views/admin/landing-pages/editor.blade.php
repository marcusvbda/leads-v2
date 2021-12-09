<landing-page-editor  
:data='@json($data)'
>
<template slot="resource-crud">
    <resource-crud 
        :data='@json($data)' 
        :params='@json($params)' 
        redirect="{{ $current_route }}" 
        data-aos="fade-right"
        :breadcrumb='@json($routes)' 
        @if (@$content)
            :content='@json($content)'
        @endif
        right_card_content="{{ $resource->crudRightCardBody() }}"
        raw_type='{{ $raw_type }}'
        :first_btn='@json($resource->firstCrudBtn())'
        :second_btn='@json($resource->secondCrudBtn())'
        :acl='@json(["can_update" => $resource->canUpdate()])'
        :crud_type='@json($resource->crudType())'
    >
    </resource-crud>    
</template>
</landing-page-editor>