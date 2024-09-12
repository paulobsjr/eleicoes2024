<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Twibbon</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f0f0f0; /* Fundo cinza claro */
      color: #333; /* Texto cinza escuro */
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      background: #fff; /* Fundo branco para o contêiner */
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      margin-top: 0;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }
    .form-group input, .form-group select {
      width: calc(100% - 22px);
      padding: 10px;
      border-radius: 4px;
      border: 1px solid #ccc; /* Borda cinza claro */
      background-color: #fff; /* Fundo branco */
      color: #333; /* Texto cinza escuro */
    }
    .form-group input::placeholder {
      color: #aaa; /* Cor do placeholder */
    }
    .form-group-inline {
      display: flex;
      flex-wrap: wrap;
      gap: 10px; /* Espaçamento entre os campos */
    }
    .form-group-inline .form-group {
      flex: 1;
      min-width: 150px; /* Largura mínima dos campos */
    }
    .card {
      text-align: center;
      margin-top: 20px;
    }
    .card h2 {
      margin-bottom: 20px;
    }
    .twibbon-container {
      display: flex;
      justify-content: center; /* Centraliza horizontalmente */
      align-items: center; /* Centraliza verticalmente */
      height: 400px; /* Altura do contêiner */
      margin: 20px 0; /* Adiciona margem acima e abaixo do contêiner */
      position: relative; /* Necessário para posicionar a moldura e a foto corretamente */
    }
    .twibbon {
      position: absolute;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden; /* Garante que a foto não saia do contêiner */
      border: 1px solid #ddd; /* Borda leve para visualização */
      background-color: #fff; /* Fundo branco */
    }
    #photo {
      position: absolute;
      z-index: 1; /* Fica atrás do twibbon */
      cursor: move;
      object-fit: contain; /* Mantém a proporção da imagem */
    }
    #twibbon {
      position: absolute;
      z-index: 2; /* Fica sobre o photo */
      width: 100%; /* Ajuste para a moldura ocupar a largura do contêiner */
      height: 100%; /* Ajuste para a moldura ocupar a altura do contêiner */
    }
    #download-btn, #portfolio-btn, #services-btn {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 20px;
      font-size: 16px;
      color: #fff;
      background-color: #007bff;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      cursor: pointer;
    }
    #download-btn:hover, #portfolio-btn:hover, #services-btn:hover {
      background-color: #0056b3;
    }
    .footer {
      text-align: center;
      margin-top: 40px;
      font-size: 14px;
    }
    .footer a {
      color: #007bff;
      text-decoration: none;
    }
    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Configurar o Twibbon</h2>
    <div class="form-group">
      <label for="twibbonimg">Escolha a moldura:</label>
      <select id="twibbonimg">
        <option value="img/z1.png">Zé do Povo 1</option>
        <option value="img/z2.png">Zé do Povo 2</option>
        <option value="img/m1.png">Maciel 1</option>
        <option value="img/m2.png">Maciel 2</option>
      </select>
    </div>
    <div class="form-group">
      <label for="photoimg">Envie sua foto:<br>
    <i>Escolha uma foto em formato quadrado com o rosto centralizado</i></label>
      <input type="file" id="photoimg" accept="image/*">
    </div>
    <div class="form-group-inline">
      <div class="form-group">
        <label for="width">Ajuste à esquerda:</label>
        <input type="text" id="width" value="100%" placeholder="100%">
      </div>
      <div class="form-group">
        <label for="height">Ajuste ao topo:</label>
        <input type="text" id="height" value="100%" placeholder="100%">
      </div>
    </div>

    <hr>

    <div class="card">
      <h2>Pré-visualização</h2>
      <div class="twibbon-container">
        <div class="twibbon">
          <img src="" id="twibbon" alt="Moldura">
          <img src="" id="photo" alt="Foto">
        </div>
      </div><br><br>
      <button id="download-btn">Baixar Imagem</button>
    </div>
  </div>

  <div class="footer">
    Desenvolvido por <a href="https://paulobsjr.github.io/site/" target="_blank">Paulo Barbosa</a><br>
    <a href="https://paulobsjr.github.io/site/" target="_blank">Meu Portfólio</a><br> 
    <a href="https://digicenter.github.io/site/" target="_blank">Serviços</a>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      var photoimg = "";

      $('#photoimg').change(function() {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
          photoimg = e.target.result;
          preview(); // Atualiza a visualização após o upload
        }
        reader.readAsDataURL(file);
      });

      $('#width, #height').on('blur', function() {
        var value = $(this).val().trim();
        if (value && !value.endsWith('%')) {
          $(this).val(value + '%');
          preview(); // Atualiza a visualização
        }
      });

      $('#width, #height, #twibbonimg').on('input', function() {
        preview();
      });

      function preview() {
        var twibbonimg = $('#twibbonimg').val();
        var width = $('#width').val();
        var height = $('#height').val();
        $("#photo").attr("src", photoimg);
        $('#twibbon').attr("src", twibbonimg);
        $('#photo').css({
          "width": width,
          "height": height
        });
      }

      preview();

      $('#photo').draggable({
        containment: '.twibbon',
        start: function(event, ui) {
          $(this).css("z-index", 10);
        },
        stop: function(event, ui) {
          $(this).css("z-index", 1);
        }
      }).resizable({
        containment: '.twibbon',
        aspectRatio: true,
        resize: function(event, ui) {
          $('#width').val(ui.size.width + 'px');
          $('#height').val(ui.size.height + 'px');
          preview();
        }
      });

      $("#download-btn").on('click', function() {
        html2canvas($(".twibbon")[0], {scale: 1}).then(canvas => {
          var imageData = canvas.toDataURL("image/png");
          var link = document.createElement('a');
          link.href = imageData;
          link.download = 'twibbon.png';
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        });
      });
    });
  </script>
</body>
</html>
