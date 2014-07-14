<?php

/**
 * i-Educar - Sistema de gest�o escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itaja�
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo
 * sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio)
 * qualquer vers�o posterior.
 *
 * Este programa � distribu��do na expectativa de que seja �til, por�m, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia impl��cita de COMERCIABILIDADE OU
 * ADEQUA��O A UMA FINALIDADE ESPEC�FICA. Consulte a Licen�a P�blica Geral
 * do GNU para mais detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto
 * com este programa; se n�o, escreva para a Free Software Foundation, Inc., no
 * endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Prefeitura Municipal de Itaja� <ctima@itajai.sc.gov.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   iEd_Pmieducar
 * @since     Arquivo dispon�vel desde a vers�o 1.0.0
 * @version   $Id$
 */

require_once 'include/clsBase.inc.php';
require_once 'include/clsDetalhe.inc.php';
require_once 'include/clsBanco.inc.php';
require_once 'include/pmieducar/geral.inc.php';
require_once 'include/modules/clsModulesPontoTransporteEscolar.inc.php';

require_once 'Portabilis/View/Helper/Application.php';


/**
 * clsIndexBase class.
 *
 * @author    Prefeitura Municipal de Itaja� <ctima@itajai.sc.gov.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   iEd_Pmieducar
 * @since     Classe dispon�vel desde a vers�o 1.0.0
 * @version   @@package_version@@
 */
class clsIndexBase extends clsBase
{
  function Formular()
  {
    $this->SetTitulo($this->_instituicao . ' i-Educar - Pontos');
    $this->processoAp = 21239;
    $this->addEstilo('localizacaoSistema');
  }
}

/**
 * indice class.
 *
 * @author    Prefeitura Municipal de Itaja� <ctima@itajai.scclsModulesPontoTransporteEscolar.gov.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   iEd_Pmieducar
 * @since     Classe dispon�vel desde a vers�o 1.0.0
 * @version   @@package_version@@
 */
class indice extends clsDetalhe
{
  var $titulo;

  function Gerar()
  {
    @session_start();
    $this->pessoa_logada = $_SESSION['id_pessoa'];
    session_write_close();

    // Verifica��o de permiss�o para cadastro.
    $this->obj_permissao = new clsPermissoes();

    $this->nivel_usuario = $this->obj_permissao->nivel_acesso($this->pessoa_logada);

    $this->titulo = 'Ponto - Detalhe';

    $cod_ponto_transporte_escolar = $_GET['cod_ponto'];
    $tmp_obj = new clsModulesPontoTransporteEscolar($cod_ponto_transporte_escolar);
    $registro = $tmp_obj->detalhe();

    if (! $registro) {
      header('Location: transporte_ponto_lst.php');
      die();
    }

    $this->addDetalhe( array("C�digo do ponto", $cod_ponto_transporte_escolar));
    $this->addDetalhe( array("Descri��o", $registro['descricao']) );

    if (is_numeric($registro['cep']) && is_numeric($registro['idlog']) && is_numeric($registro['idbai'])){
      $this->addDetalhe( array("CEP", int2CEP($registro['cep'])) );
      $this->addDetalhe( array("Munic�pio", $registro['municipio']) );
      $this->addDetalhe( array("Distrito", $registro['distrito']) );
      $this->addDetalhe( array("Bairro", $registro['bairro']) );
      $this->addDetalhe( array("Zona de localiza��o", $registro['zona_localizacao'] == 1 ? 'Urbana' : 'Rural' ) );
      $this->addDetalhe( array("Endere�o", $registro['idtlog'] . ' ' . $registro['logradouro']) );
      $this->addDetalhe( array("N�mero", $registro['numero']) );
      $this->addDetalhe( array("Complemento", $registro['complemento']) );
    }

    $this->url_novo = "../module/TransporteEscolar/Ponto";
    $this->url_editar = "../module/TransporteEscolar/Ponto?id={$cod_ponto_transporte_escolar}";
    $this->url_cancelar = "transporte_ponto_lst.php";

    $this->largura = "100%";

    $localizacao = new LocalizacaoSistema();
    $localizacao->entradaCaminhos( array(
         $_SERVER['SERVER_NAME']."/intranet" => "In&iacute;cio",
         "educar_index.php"                  => "i-Educar - Escola",
         ""                                  => "Detalhe do ponto"
    ));
    $this->enviaLocalizacao($localizacao->montar());
  }
}

// Instancia o objeto da p�gina
$pagina = new clsIndexBase();

// Instancia o objeto de conte�do
$miolo = new indice();

// Passa o conte�do para a p�gina
$pagina->addForm($miolo);

// Gera o HTML
$pagina->MakeAll();
