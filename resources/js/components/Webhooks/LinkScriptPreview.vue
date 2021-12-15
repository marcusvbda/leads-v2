<template>
    <div>
        <h1>Script para request direto</h1>
        <p>Insira insira este código em sua landing page para capturar diretamente o request</p>
        <p class="mt-4 text-muted">Copie o código e cole no head de seu site para começar a enviar requests diretos para este webhook</p>

        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid">
                <code>
                   {{'<'}}script type='text/javascript' src='{{ content.url_script }}'></script>
                </code>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["content"],
};
</script>
