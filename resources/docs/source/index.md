---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://local.ezcore.leads-v2/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_f8d53bffadac8ce30ccbbede4975b4ff -->
## Testar a Autenticação

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://local.ezcore.leads-v2/api/v1/test-auth" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}"
```

```javascript
const url = new URL(
    "http://local.ezcore.leads-v2/api/v1/test-auth"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Basic {token}",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "id": 1,
    "name": "Nx Acadêmico",
    "env": "homologation"
}
```

### HTTP Request
`POST api/v1/test-auth`


<!-- END_f8d53bffadac8ce30ccbbede4975b4ff -->

<!-- START_2313ee72673fa52676e50ee059029cb0 -->
## Listar todos os eventos disponíveis

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://local.ezcore.leads-v2/api/v1/get-events" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}"
```

```javascript
const url = new URL(
    "http://local.ezcore.leads-v2/api/v1/get-events"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Basic {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "registration-store-or-update": {
        "description": "Atualizará ou criará um novo cadastro no sistema ( caso a integration_key não exista na base ), sem influenciar em status",
        "rules": {
            "params.data": [
                "required"
            ]
        }
    },
    "registred-student": {
        "description": "Informará ao CRM que o aluno se cadastrou no sistema acadêmico, mudará o status para aluno cadastrado",
        "rules": {
            "params.data": [
                "required"
            ]
        }
    },
    "waiting-exame": {
        "description": "Informará ao CRM que o aluno está pronto para prestar o vestibular, mudará o status para aguardando vestibular",
        "rules": {
            "params.subscription_key": [
                "required"
            ]
        }
    },
    "passed-the-test": {
        "description": "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para aprovado no vestibular",
        "rules": {
            "params.subscription_key": [
                "required"
            ]
        }
    },
    "failed-the-test": {
        "description": "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para reprovado no vestibular",
        "rules": {
            "params.subscription_key": [
                "required"
            ]
        }
    },
    "pre-subscripted": {
        "description": "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para aprovado no pré-matriculado",
        "rules": {
            "params.subscription_key": [
                "required"
            ]
        }
    },
    "subscripted": {
        "description": "Informará ao CRM que o aluno foi aprovado no vestibular, mudará o status para aprovado no matriculado",
        "rules": {
            "params.subscription_key": [
                "required"
            ]
        }
    }
}
```

### HTTP Request
`GET api/v1/get-events`


<!-- END_2313ee72673fa52676e50ee059029cb0 -->

<!-- START_98bcf786abb1dfbe213cdb3e777815c7 -->
## Listar todos as actions disponíveis

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://local.ezcore.leads-v2/api/v1/get-actions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}"
```

```javascript
const url = new URL(
    "http://local.ezcore.leads-v2/api/v1/get-actions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Basic {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    "lead-update"
]
```

### HTTP Request
`GET api/v1/get-actions`


<!-- END_98bcf786abb1dfbe213cdb3e777815c7 -->

<!-- START_c9b45bbcf97d30ce6fd444d4fd3008ca -->
## Disparar eventos para a action selecionada

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://local.ezcore.leads-v2/api/v1/event-handler" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}" \
    -d '{"action":"quod","params":{"integration_key":"ad","event":"quia","data":"sed","subscription_key":"accusamus"}}'

```

```javascript
const url = new URL(
    "http://local.ezcore.leads-v2/api/v1/event-handler"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Basic {token}",
};

let body = {
    "action": "quod",
    "params": {
        "integration_key": "ad",
        "event": "quia",
        "data": "sed",
        "subscription_key": "accusamus"
    }
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/event-handler`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `action` | string |  required  | Precisa ser uma das actions habilitadas, você pode listar todas actions na routa get-actions'.
        `params.integration_key` | string |  required  | key única de refêrencia'.
        `params.event` | string |  required  | nome do evento que será disparado para a action selecionado, você pode listar todos os eventos na routa get-events'.
        `params.data` | required |  optional  | object dados adicionais que serão utilizados por alguns eventos, por exemplo, no caso de lead update, deve-se passar as informações do lead neste parâmetro'.
        `params.subscription_key` | required |  optional  | string adicional necessário para quase todos os eventos habilitados'.
    
<!-- END_c9b45bbcf97d30ce6fd444d4fd3008ca -->


