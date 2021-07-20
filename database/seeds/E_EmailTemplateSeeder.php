<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
	EmailTemplate
};

class E_EmailTemplateSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createEmails();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function createEmails()
	{
		EmailTemplate::truncate();

		$this->singleColumn();
		$this->twoColumnsAndText();
		$this->twoAndThreeColumns();
		$this->textAndThreeColumns();
		$this->twoColumns();
		$this->threeColumnsAndText();
	}

	private function threeColumnsAndText()
	{
		EmailTemplate::create([
			"name" => "3 colunas e Texto",
			"slug" => "3_colunas_e_texto",
			"thumbnail" => "/assets/images/email_templates/three_columns_and_text.png",
			"body" => (object)[
				"css" => "",
				"body" => '
				<table id="ikuc" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="i1mc" style="box-sizing: border-box;">
						<tr id="ijlk" style="box-sizing: border-box;">
						<td id="idl9" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;">
							<img id="i55t" src="https://via.placeholder.com/600x203?text=600px%20de%20largura" style="box-sizing: border-box; color: black; width: 100%;">
						</td>
						</tr>
					</tbody>
					</table>
					<table id="il1stb" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="ii0k4" style="box-sizing: border-box;">
						<tr id="iia79" style="box-sizing: border-box;">
						<td id="ievpml" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;">
							<table id="ix1qpi" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
							<tbody style="box-sizing: border-box;">
								<tr style="box-sizing: border-box;">
								<td id="iowk0j" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="iphisy" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="id2smj" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="i1jksb" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="ifh4xp" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="i17431" style="box-sizing: border-box; padding: 10px;">
									<h3 id="it8d75" style="box-sizing: border-box; text-align: center;">Título
									</h3>
									<p id="injj8x" style="box-sizing: border-box; text-align: center;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
						</td>
						</tr>
					</tbody>
					</table>
					<div id="ibojza" style="box-sizing: border-box; padding: 10px; text-align: center;">
					<span id="inn8ht" style="box-sizing: border-box; text-align: center;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email</span>
				</div>'
			]
		]);
	}

	private function twoColumns()
	{
		EmailTemplate::create([
			"name" => "2 colunas",
			"slug" => "2_colunas",
			"thumbnail" => "/assets/images/email_templates/two_columns.png",
			"body" => (object)[
				"css" => "",
				"body" => '
				<table id="ikuc" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="i1mc" style="box-sizing: border-box;">
						<tr id="ijlk" style="box-sizing: border-box;">
						<td id="idl9" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;">
							<img id="i55t" src="https://via.placeholder.com/600x203?text=600px%20de%20largura" style="box-sizing: border-box; color: black; width: 100%;">
						</td>
						</tr>
					</tbody>
					</table>
					<table id="il1stb" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="ii0k4" style="box-sizing: border-box;">
						<tr id="iia79" style="box-sizing: border-box;">
						<td id="ievpml" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;">
							<table id="iiupq" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
							<tbody style="box-sizing: border-box;">
								<tr id="id4pd" style="box-sizing: border-box;">
								<td id="iwrut" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="iphisy" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="ijdph" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="i00x3l" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
						</td>
						</tr>
					</tbody>
				</table>'
			]
		]);
	}

	private function textAndThreeColumns()
	{
		EmailTemplate::create([
			"name" => "Texto e 3 colunas",
			"slug" => "2_e_3_colunas",
			"thumbnail" => "/assets/images/email_templates/text_and_three_columns.png",
			"body" => (object)[
				"css" => "",
				"body" => '
				<table id="ikuc" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="i1mc" style="box-sizing: border-box;">
						<tr id="ijlk" style="box-sizing: border-box;">
						<td id="idl9" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;">
							<img id="i55t" src="https://via.placeholder.com/600x203?text=600px%20de%20largura" style="box-sizing: border-box; color: black; width: 100%;">
						</td>
						</tr>
					</tbody>
					</table>
					<div id="iqd4ld" style="box-sizing: border-box; padding: 10px; text-align: center;">
					<span id="ibno2g" style="box-sizing: border-box; text-align: center;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email</span>
					</div>
					<table id="il1stb" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="ii0k4" style="box-sizing: border-box;">
						<tr id="iia79" style="box-sizing: border-box;">
						<td id="ievpml" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;">
							<table id="iiupq" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
							<tbody style="box-sizing: border-box;">
								<tr id="id4pd" style="box-sizing: border-box;">
								<td id="iwrut" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="iphisy" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="ioa8c" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="iftjaw" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="ijdph" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="i00x3l" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
						</td>
						</tr>
					</tbody>
				</table>'
			]
		]);
	}

	private function twoAndThreeColumns()
	{
		EmailTemplate::create([
			"name" => "2 e 3 colunas",
			"slug" => "2_e_3_colunas",
			"thumbnail" => "/assets/images/email_templates/two_and_three_columns.png",
			"body" => (object)[
				"css" => "",
				"body" => '
				<table id="ikuc" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="i1mc" style="box-sizing: border-box;">
						<tr id="ijlk" style="box-sizing: border-box;">
						<td id="idl9" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;">
							<img id="i55t" src="https://via.placeholder.com/600x203?text=600px%20de%20largura" style="box-sizing: border-box; color: black; width: 100%;">
						</td>
						</tr>
					</tbody>
					</table>
					<div id="ioi7a" style="box-sizing: border-box; padding: 10px; font-family: Arial Black, Gadget, sans-serif; text-align: center; font-size: 30px;">Título
					</div>
					<table id="i7gkg" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="ix57" style="box-sizing: border-box;">
						<tr id="ip8m6" style="box-sizing: border-box;">
						<td id="iqg4h" width="50%" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%; text-align: center;">
							<img id="ib9kb" src="https://via.placeholder.com/250x162?text=250px%20de%20largura" style="box-sizing: border-box; color: black;">
						</td>
						<td id="ijcku" width="50%" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%; text-align: center;">
							<img id="i5984" src="https://via.placeholder.com/250x162?text=250px%20de%20largura" style="box-sizing: border-box; color: black;">
						</td>
						</tr>
					</tbody>
					</table>
					<table id="il1stb" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="ii0k4" style="box-sizing: border-box;">
						<tr id="iia79" style="box-sizing: border-box;">
						<td id="ievpml" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;">
							<table id="izjed" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
							<tbody id="irdcm" style="box-sizing: border-box;">
								<tr id="iuo8l" style="box-sizing: border-box;">
								<td id="irbrw" width="50%" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%;">
									<div id="i1bql" style="box-sizing: border-box; padding: 10px;">
									<h2 id="iof0c" style="box-sizing: border-box;">Título
									</h2>
									<p id="idnk7" style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									<h2 id="ixrdn" style="box-sizing: border-box;">
									</h2>
									</div>
								</td>
								<td id="i0oq2" width="50%" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%;">
									<div id="i5mqi" style="box-sizing: border-box; padding: 10px;">
									<h2 id="i15pl" style="box-sizing: border-box;">Título
									</h2>
									<p id="il1w2" style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
							<table id="iiupq" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
							<tbody style="box-sizing: border-box;">
								<tr style="box-sizing: border-box;">
								<td id="iwrut" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="iphisy" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="ioa8c" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="iftjaw" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								<td id="ijdph" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 33.3333%;" width="33.3333%" valign="top">
									<div id="i00x3l" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
							<table id="iuzh1f" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
							<tbody id="icosd" style="box-sizing: border-box;">
								<tr id="iahx2" style="box-sizing: border-box;">
								<td id="i5cxhs" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;">
									<div id="imtj45" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 id="icgxx" style="box-sizing: border-box;">Título
									</h3>
									<p id="irqbk" style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
							<a target="_blank" href="#" id="in30vf" style="box-sizing: border-box; border: 0 solid #fbfbfb; border-collapse: separate; border-radius: 10px 10px 10px 10px; font-family: Arial Black, Gadget, sans-serif; padding: 10px 30px 10px 30px; color: #ffffff; background-color: #f04809; width: 100%; max-width: 100%;">
							Clique para ter mais informações
							</a>
						</td>
						</tr>
					</tbody>
				</table>'
			]
		]);
	}

	private function twoColumnsAndText()
	{
		EmailTemplate::create([
			"name" => "2 colunas e texto",
			"slug" => "2_colunas_texto",
			"thumbnail" => "/assets/images/email_templates/two_columns_and_text.png",
			"body" => (object)[
				"css" => "",
				"body" => '
				<table id="ikuc" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="i1mc" style="box-sizing: border-box;">
						<tr id="ijlk" style="box-sizing: border-box;">
						<td id="idl9" valign="top" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;">
							<img id="i55t" src="https://via.placeholder.com/600x203?text=600px%20de%20largura" style="box-sizing: border-box; color: black; width: 100%;">
						</td>
						</tr>
					</tbody>
					</table>
					<div id="ioi7a" style="box-sizing: border-box; padding: 10px; font-family: Arial Black, Gadget, sans-serif; text-align: center; font-size: 30px;">Título
					</div>
					<table id="i7gkg" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
					<tbody style="box-sizing: border-box;">
						<tr style="box-sizing: border-box;">
						<td id="iqg4h" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%; text-align: center;" width="50%" valign="top" align="center">
							<img id="ib9kb" src="https://via.placeholder.com/250x162?text=250px%20de%20largura" style="box-sizing: border-box; color: black;">
						</td>
						<td id="ijcku" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%; text-align: center;" width="50%" valign="top" align="center">
							<img id="i5984" src="https://via.placeholder.com/250x162?text=250px%20de%20largura" style="box-sizing: border-box; color: black;">
						</td>
						</tr>
					</tbody>
					</table>
					<table id="il1stb" width="100%" height="150" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;">
					<tbody id="ii0k4" style="box-sizing: border-box;">
						<tr id="iia79" style="box-sizing: border-box;">
						<td id="ievpml" valign="top" align="center" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;">
							<table id="izjed" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
							<tbody style="box-sizing: border-box;">
								<tr id="iuo8l" style="box-sizing: border-box;">
								<td id="irbrw" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%;" width="50%" valign="top">
									<div id="i1bql" style="box-sizing: border-box; padding: 10px;">
									<h2 style="box-sizing: border-box;">Título
									</h2>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									<h2 style="box-sizing: border-box;">
									</h2>
									</div>
								</td>
								<td id="i0oq2" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; width: 50%;" width="50%" valign="top">
									<div id="i5mqi" style="box-sizing: border-box; padding: 10px;">
									<h2 style="box-sizing: border-box;">Título
									</h2>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
							<table id="iuzh1f" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
							<tbody style="box-sizing: border-box;">
								<tr style="box-sizing: border-box;">
								<td id="i5cxhs" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;" valign="top">
									<div id="imtj45" style="box-sizing: border-box; padding: 10px; text-align: center;">
									<h3 style="box-sizing: border-box;">Título
									</h3>
									<p style="box-sizing: border-box;">Este é um componente de texto. Você pode utilizá-lo para adicionar texto na sua campanha de email
									</p>
									</div>
								</td>
								</tr>
							</tbody>
							</table>
							<a target="_blank" href="#" id="in30vf" style="box-sizing: border-box; background-color: #586ac0; border: 0 solid #fbfbfb; border-collapse: separate; border-radius: 10px 10px 10px 10px; font-family: Arial Black, Gadget, sans-serif; padding: 10px 30px 10px 30px; color: #ffffff;">
							Clique para ter mais informações
							</a>
						</td>
						</tr>
					</tbody>
				</table>'
			]
		]);
	}

	private function singleColumn()
	{
		EmailTemplate::create([
			"name" => "1 Coluna",
			"slug" => "1_coluna",
			"thumbnail" => "/assets/images/email_templates/single_column.png",
			"body" => (object)[
				"css" => "",
				"body" => '
				<table id="ikuc" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
					<tbody style="box-sizing: border-box;">
						<tr style="box-sizing: border-box;">
						<td id="idl9" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;" valign="top">
							<img id="i55t" src="https://via.placeholder.com/600x203?text=600px%20de%20largura" style="box-sizing: border-box; color: black; width: 100%;">
						</td>
						</tr>
					</tbody>
					</table>
					<table id="ikjg" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
					<tbody style="box-sizing: border-box;">
						<tr style="box-sizing: border-box;">
						<td id="ifpc" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top;" valign="top">
							<div id="iws7j" style="box-sizing: border-box; padding: 10px;">
							<h1 id="ixd2l" style="box-sizing: border-box; font-size: 24px; color: rgb(123, 119, 113); line-height: 110%;">eBook: Adipiscing et interdum dignissim
							</h1>
							</div>
							<div id="ixtmi" style="box-sizing: border-box; padding: 10px;">
							<p style="box-sizing: border-box;">Olá,
							</p>
							<p style="box-sizing: border-box;">Aenean dolor massa, adipiscing eu interdum ut, volutpat nec mi. Mauris vitae accumsan dolor. Mauris adipiscing, enim nec vestibulum sodales, ligula mauris lobortis eros, vel iaculis diam libero in mauris. Ut et ipsum imperdiet purus facilisis interdum. Aliquam erat volutpat. Morbi egestas nisi volutpat felis ultrices molestie. Integer gravida, eros ut lobortis auctor ornare.
							</p>
							</div>
						</td>
						</tr>
					</tbody>
					</table>
					<table id="ikl0h" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
					<tbody style="box-sizing: border-box;">
						<tr style="box-sizing: border-box;">
						<td id="i17gk" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;" valign="top" align="center">
							<img id="inx76" src="https://via.placeholder.com/250x162?text=250px%20de%20largura" style="box-sizing: border-box; color: black;">
						</td>
						</tr>
					</tbody>
					</table>
					<div id="isarf" style="box-sizing: border-box; padding: 10px;">Cras mauris magna, dignissim in blandit ac, pretium vitae lacus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					</div>
					<div id="i2qibd" style="box-sizing: border-box; padding: 10px;">
					<p style="box-sizing: border-box;">Cras mauris magna, dignissim in blandit ac, pretium vitae lacus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
					</p>
					<ul style="box-sizing: border-box;">
						<li style="box-sizing: border-box;">
						<strong style="box-sizing: border-box;">Cras mauris magna:</strong>
						<br style="box-sizing: border-box;">Donec elit erat, malesuada ac sollicitudin imperdiet, imperdiet sed leo. Morbi ut odio ante, non ultrices 10 fringilla ac luctus in. Nulla in orci in lacus gravida posuere. Sed ligula odio, vestibulum vel auctor sit amet, luctus at leo.
						</li>
						<li style="box-sizing: border-box;">
						<strong style="box-sizing: border-box;">Lorem ipsum dolor:</strong>
						<br style="box-sizing: border-box;">Morbi ut odio ante, non ultrices nibh. Nulla in orci in lacus gravida posuere. Sed ligula odio, vestibulum vel auctor sit amet, luctus at leo. Suspendisse id nulla est, in scelerisque dolor. Aenean gravida imperdiet sapien.
						</li>
					</ul>
					</div>
					<table id="il1stb" style="box-sizing: border-box; height: 150px; margin: 0 auto 10px auto; padding: 5px 5px 5px 5px; width: 100%;" width="100%" height="150">
					<tbody style="box-sizing: border-box;">
						<tr style="box-sizing: border-box;">
						<td id="ievpml" style="box-sizing: border-box; padding: 0; margin: 0; vertical-align: top; text-align: center;" valign="top" align="center">
							<a target="_blank" href="#" id="in30vf" style="box-sizing: border-box; background-color: #586ac0; border: 0 solid #fbfbfb; border-collapse: separate; border-radius: 10px 10px 10px 10px; font-family: Arial Black, Gadget, sans-serif; padding: 10px 30px 10px 30px; color: #ffffff;">
								Clique para ter mais informações
							</a>
						</td>
						</tr>
					</tbody>
				</table>'
			]
		]);
	}
}