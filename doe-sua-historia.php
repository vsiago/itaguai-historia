<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'):

  if(isset($_POST['enviar'])):
        
$nome     = $_POST['nome'];
$fone     = $_POST['telefone'];
$email      = $_POST['email'];
$endereco   = $_POST['endereco'];
$bairro     = $_POST['bairro'];
$maisDetalhes   = $_POST['maisDetalhes'];

$data      = date("d/m/y");
$ip        = $_SERVER['REMOTE_ADDR'];
//$navegador = $_SERVER['HTTP_USER_AGENT'];
$hora      = date("H:i");

$secretKey    = "6LcRYU8UAAAAAEU5wZuZ3R2U8pbMYLVNRVCXXEEn";
$responseKey  = $_POST['g-recaptcha-response'];
  

  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$ip";

  $resposta = file_get_contents($url);
  $resposta = json_decode($resposta);
  if($resposta->success):
    
    if (($nome=="")||($fone=="")|| ($email=="")){
  $msg = '
      <center class="aviso">
    <h5>OPS!!!</h5>
    <p>
       Ocorreu um erro, por favor, preencha todos os campos do formulário.<br />
       tente novamente.!!
    </p>
    </center>
    <br />
  ';
  }
  else{
    
$mime_list = array("html"=>"text/html","htm"=>"text/html", "txt"=>"text/plain", "rtf"=>"text/enriched","csv"=>"text/tab-separated-values","css"=>"text/css","gif"=>"image/gif"); 

$ABORT = FALSE; 

$boundary = "XYZ-" . date(dmyhms) . "-ZYX"; 

$message = "--$boundary\n"; 
$message .= "Content-Transfer-Encoding: 8bits\n"; 
$message .= "Content-Type: text/html; charset=\"UTF-8\"\n\n"; 
$message .= "<b>Nome:</b> $nome<br>
<b>Data:</b> $data<br>
<b>Hora:</b> $hora \n<br>
<b>Ip:</b> $ip \n<br>
<b>Telefone:</b> $fone \n<br>
<b>E-mail:</b> $email \n<br>
<b>Assunto:</b> Especial200 anos \n<br>
<b>Endereço:</b> ".$_POST['endereco']." \n<br>
<b>Bairro:</b> $bairro \n<br>
<b>Descrição:</b> $maisDetalhes\n";
$message .= "--$boundary\n";

if(isset($_FILES["file"]["name"])):

    for($i = 0; $i < count($_FILES["file"]["name"]); $i++)
    {
        if(is_uploaded_file($_FILES["file"]["tmp_name"][$i])){
            $fp = fopen($_FILES["file"]["tmp_name"][$i], "rb");
            $anexo = chunk_split(base64_encode(fread($fp, $_FILES["file"]["size"][$i])));       
            fclose($fp);

            $message .= "Content-Type: ".$_FILES["file"]["type"][$i]."\n name=\"".$_FILES["file"]["name"][$i]."\"\n";
            $message .= "Content-Disposition: attachment; filename=\"".$_FILES["file"]["name"][$i]."\"\n";     
            $message .= "Content-transfer-encoding:base64\n\n";
            $message .= $anexo."\n";
            
            if($i + 1 == count($_FILES["file"]["name"]))
                $message.= "--$boundary--";
            else
                $message.= "--$boundary\n";
            
            if($_FILES["file"]['error'][$i] == 0) {
                $anexos++;
            }       
        }   
    }

$message .= "--$boundary--\r\n"; 
endif;

$headers = "MIME-Version: 1.0\n"; 
$headers .= "Date: ".date("D, d M Y H:i:s O")."\n";
$headers .= "From: \"$nome\" <".$email."> \r\n"; 
$headers .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n"; 

//$to = "rodrigueslp@outlook.com";

$to = "faleconosco@itaguai.rj.gov.br";

  $mensagem=mail($to, 'Fale Conosco - Prefeitura de Itaguaí'.$assunto, $message, $headers);

    if($mensagem): 
    $site = "MIME-Version: 1.0\n"; 
    $site .= "From: $to\r\n"; 
    $site .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n"; 
    $titulo = "A Prefeitura Municipal de Itaguai agradece pelo contato!";
    $msg = "--$boundary\n"; 
    $msg .= "Content-Transfer-Encoding: 8bits\n"; 
    $msg .= "Content-Type: text/html; charset=\"UTF-8\"\n\n"; 
    $msg .= "\n<b>$nome</b>, obrigado por entrar em contato conosco. Sua mensagem será encaminhada ao setor responsável. Entraremos em contato, se necessário.<br /><br />
    Esta mensagem é automática. Por favor, não responda.<br /><br />
    <font color='blue'><b>Prefeitura Municipal de Itaguaí</b></font><br />
    Rua General Bocaiúva, 636, Centro - Itaguaí/RJ. CEP: 23.815-310.<br />
    Tel.: (21) 3782-9000 <br /><br />
    <img src='http://itaguai.rj.gov.br/img/logo_pref_itaguai.png' title='Prefeitura de Itaguaí' alt='Prefeitura de Itaguaí' />";
    
    mail("$email","$titulo","$msg","$site");
      $msg = '      
      <center class="succes">
      <h5>Sucesso!</h5>
        <p>
           Sua mensagem foi enviada. Você receberá um e-mail automático de confirmação.
        </p>
      </center>
      <meta http-equiv="refresh" content="3; URL=./" />
      ';        
    
    else:
      $msg = '
      
      <center class="aviso">
      <h5>Erro!</h5>
        <p>
           Ocorreu um erro ao processar sua mensagem, tente novamente!
        </p>
      </center>
      <br /><br />
        <meta http-equiv="refresh" content="3; URL=fale-conosco.php" />
      '; 
    endif;

}//fim se nao vazio

  else:
    echo "Não foi possível verificar o captcha.  Ip capturado: ".$ip;
  endif;
  
/*

*/
  else:
   $msg = 'Ocorreu um erro inesperado ao enviar sua mensagem.';
endif;

endif;
?> 
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>História de Itaguaí - 202 Anos / Doe sua história</title>
    <link rel="shortcut icon" href="http://itaguai.rj.gov.br/images/fav.png">
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/agency.min.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="./"><img src="img/logos/SELO-202-ITAGUAI.png" /></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.html#about">Linha do Tempo</a>
            </li>

            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="index.html#team">Filhos ilustres</a>
            </li>

            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="imagens.html">Imagens históricas</a>
            </li>

            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="livro.html">Livro</a>
            </li>  

            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="doe-sua-historia.php">Doe sua história</a>
            </li>
           
          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
    
    
    <!-- Portfolio Grid -->
    <section class="bg-light" id="portfolio" style="padding:150px 0; width: 100%;"> 
      <div class="container">

        <?php if(isset($msg) && $msg!=NULL): echo $msg; endif; ?>
        
          <p>Todos que tiveram alguma história com Itaguaí, sejam atuais moradores ou não, podem contribuir com este importante espaço.</p>
<p>
Se você tem alguma foto, vídeo ou qualquer registro antigo de Itaguaí, entre em contato conosco, através do formulário abaixo, para doar seu material.</p>
<p>
<b>Você é parte da história!</b></p>


        <div class="col-md-12">
          <br />
          <p>
            <?php if(isset($msg) && $msg!=NULL): echo $msg; endif; ?>
            
          </p>
          <form method="post" action="" class="aSubmit" enctype="multipart/form-data">
            

            <div style="display:none"><input type="text" name="maximus" value=""></div>
            <input type="hidden" name="theSubject" value="Fale conosco - prefeitura de Itaguaí">
            <div class="row">
              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  <input type="text" placeholder="Nome*" name="nome" required>
                </div>
              </div>
              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  <input type="text" placeholder="Telefone*" name="telefone" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  <input type="email" placeholder="E-mail*" value="" name="email" required>
                </div>
              </div>
              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  <input type="text" placeholder="Endereço" required name="endereco">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  <input type="text" placeholder="Bairro*"  name="bairro">
                </div>
              </div>
              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  
                   <div style="display: block; padding: 30px 0 30px 0; clear: both; width: 95%; overflow: hidden; ">
                      <label>Fotos ou vídeos:</label>
                      <input type="file" name="file[]" id="file" multiple />
                      <input type="hidden" name="enviar" value="1">
                    </div>

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-md-6">
                <div class="form-group">
                </div>
              </div>

              <div class="col-xs-12 col-md-6">
                <div class="form-group">

                </div>
              </div>

              <div class="col-xs-12">
               
              </div>

              <div class="col-xs-12 col-md-12">
                <div class="form-group">
                  <textarea placeholder="Mensagem*" aria-invalid="false" rows="10" style="min-width: 100%;" name="maisDetalhes" required></textarea>
                </div>

                <div class="g-recaptcha" data-sitekey="6LcRYU8UAAAAAJtR9mgTdLEtDmnVu5sJD29LY0xg"></div>

                <input type="submit" class="btn btn-primary" value="ENVIAR MENSAGEM">
                <img class="ajax-loader" id="loader" src="images/ajax-loader.gif" alt="ENVIANDO ..." style="display: none;">
              </div>
            </div>
            


            
          </form>
        </div>


      </div>
    </section>


    

   
    <!-- Footer -->
    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <span class="copyright">Prefeitura de Itaguaí</span>
          </div>
          <div class="col-md-4">
            <ul class="list-inline social-buttons">
              <li class="list-inline-item">
                <a href="https://www.twitter.com/prefitaguai/">
                  <i class="fa fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="https://www.facebook.com/prefeituraitaguai/">
                  <i class="fa fa-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="https://www.instagram.com/prefeituraitaguai/">
                  <i class="fa fa-instagram"></i>
                </a>
              </li>
            </ul>
          </div>
          
        </div>
      </div>
    </footer>

    
    <!-- Modals Fotos  -->

    <!-- Modal Foto 1 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto1" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 1</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Foto 1 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto1" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 1</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal Foto 1 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto1" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 1</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal Foto 2 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto2" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 2</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Foto 3 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto3" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 3</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Foto 4 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto4" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 4</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Foto 5 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto5" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 5</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Foto 6 -->
    <div class="portfolio-modal modal fade" id="portfolioModalFoto6" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="close-modal" data-dismiss="modal">
            <div class="lr">
              <div class="rl"></div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="modal-body">
                  <!-- Project Details Go Here -->
                  <h2 class="text-uppercase">Foto 6</h2>
                  <p class="item-intro text-muted"></p>
                  <img class="img-fluid d-block mx-auto" src="img/portfolio/200/senai.jpg">
                  <p></p>
                  
                  <button class="btn btn-primary" data-dismiss="modal" type="button">
                    <i class="fa fa-times"></i>
                    Fechar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Fim das fotos -->


    <!-- Bootstrap core JavaScript -->

    <script src='https://www.google.com/recaptcha/api.js'></script>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/agency.min.js"></script>

  </body>

</html>
