<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Reference</title>

    <link rel="stylesheet" href="{{ asset('/docs/css/style.css') }}" />
    <script src="{{ asset('/docs/js/all.js') }}"></script>


          <script>
        $(function() {
            setupLanguages(["bash","javascript"]);
        });
      </script>
      </head>

  <body class="">
    <a href="#" id="nav-button">
      <span>
        NAV
        <img src="/docs/images/navbar.png" />
      </span>
    </a>
    <div class="tocify-wrapper">
        <img src="/docs/images/logo.png" />
                    <div class="lang-selector">
                                  <a href="#" data-language-name="bash">bash</a>
                                  <a href="#" data-language-name="javascript">javascript</a>
                            </div>
                            <div class="search">
              <input type="text" class="search" id="input-search" placeholder="Search">
            </div>
            <ul class="search-results"></ul>
              <div id="toc">
      </div>
                    <ul class="toc-footer">
                                  <li><a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a></li>
                            </ul>
            </div>
    <div class="page-wrapper">
      <div class="dark-box"></div>
      <div class="content">
          <!-- START_INFO -->
<h1>Info</h1>
<p>Welcome to the generated API reference.
<a href="{{ route("apidoc.json") }}">Get Postman Collection</a></p>
<!-- END_INFO -->
<h1>general</h1>
<!-- START_f8d53bffadac8ce30ccbbede4975b4ff -->
<h2>Testar a Autenticação</h2>
<p><br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small></p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://local.ezcore.leads-v2/api/v1/test-auth" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}"</code></pre>
<pre><code class="language-javascript">const url = new URL(
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
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "id": 1,
    "name": "Nx Acadêmico",
    "env": "homologation"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/v1/test-auth</code></p>
<!-- END_f8d53bffadac8ce30ccbbede4975b4ff -->
<!-- START_2313ee72673fa52676e50ee059029cb0 -->
<h2>Listar todos os eventos disponíveis</h2>
<p><br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small></p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://local.ezcore.leads-v2/api/v1/get-events" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}"</code></pre>
<pre><code class="language-javascript">const url = new URL(
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
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
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
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/v1/get-events</code></p>
<!-- END_2313ee72673fa52676e50ee059029cb0 -->
<!-- START_98bcf786abb1dfbe213cdb3e777815c7 -->
<h2>Listar todos as actions disponíveis</h2>
<p><br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small></p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://local.ezcore.leads-v2/api/v1/get-actions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}"</code></pre>
<pre><code class="language-javascript">const url = new URL(
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
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">[
    "lead-update"
]</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/v1/get-actions</code></p>
<!-- END_98bcf786abb1dfbe213cdb3e777815c7 -->
<!-- START_c9b45bbcf97d30ce6fd444d4fd3008ca -->
<h2>Disparar eventos</h2>
<p><br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small></p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://local.ezcore.leads-v2/api/v1/event-handler" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Basic {token}" \
    -d '{"action":"debitis","params":{"integration_key":"occaecati","event":"maiores","data":{}}}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://local.ezcore.leads-v2/api/v1/event-handler"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Basic {token}",
};

let body = {
    "action": "debitis",
    "params": {
        "integration_key": "occaecati",
        "event": "maiores",
        "data": {}
    }
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/v1/event-handler</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>action</code></td>
<td>string</td>
<td>required</td>
<td>Precisa ser uma das actions habilitadas, você pode listar todas actions na routa get-actions'.</td>
</tr>
<tr>
<td><code>params.integration_key</code></td>
<td>string</td>
<td>required</td>
<td>key única de refêrencia'.</td>
</tr>
<tr>
<td><code>params.event</code></td>
<td>string</td>
<td>required</td>
<td>nome do evento que será disparado para a action selecionado, você pode listar todos os eventos na routa get-events'.</td>
</tr>
<tr>
<td><code>params.data</code></td>
<td>object</td>
<td>optional</td>
<td>optional dados adicionais que serão utilizados para o evento, por exemplo, no caso de lead update, deve-se passar as informações do lead neste parâmetro'.</td>
</tr>
</tbody>
</table>
<!-- END_c9b45bbcf97d30ce6fd444d4fd3008ca -->
      </div>
      <div class="dark-box">
                        <div class="lang-selector">
                                    <a href="#" data-language-name="bash">bash</a>
                                    <a href="#" data-language-name="javascript">javascript</a>
                              </div>
                </div>
    </div>
  </body>
</html>