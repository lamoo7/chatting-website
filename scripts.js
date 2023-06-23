window.onload = function() {
    var chatbox = document.getElementById('chatbox');
    chatbox.scrollTop = chatbox.scrollHeight;
  }
  
  $(document).ready(function() {
    $("#submitmsg").click(function() {
      var clientmsg = $("#usermsg").val();
      var urlParams = new URLSearchParams(window.location.search);
      var roomId = urlParams.get('id');
  
      if (roomId) {
        $.post("post.php?id=" + roomId, { text: clientmsg }, function(data) {
        });
        $("#usermsg").val("");
      }
      return false;
    });
  
    let previousSize = 0;
    function loadLog() {
      var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20;
      var urlParams = new URLSearchParams(window.location.search);
      var roomId = urlParams.get('id');
  
      if (roomId) {
        $.ajax({
          url: "logs/log-" + roomId + ".html",
          cache: false,
          success: function(html) {
            let currentSize = html.length;
            if (currentSize !== previousSize) {
              previousSize = currentSize;
              $("#chatbox").html(html);
  
              var newscrollHeight = $("#chatbox")[0].scrollHeight - 20;
              if (newscrollHeight > oldscrollHeight) {
                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
              }
            }
          }
        });
      }
    }
    setInterval(loadLog, 1000);
  });
  
  
  function search() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("user-search");
      filter = input.value.toUpperCase();
      table = document.getElementById("UL");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[0];
          if (td) {
              txtValue = td.textContent || td.innerText;
              if (txtValue.toUpperCase().indexOf(filter) > -1) {
                  tr[i].style.display = "";
              } else {
                  tr[i].style.display = "none";
              }
          }       
      }
  }
  const hamburger = document.querySelector(".hamburger");
  const layers = document.querySelectorAll(".hamburger span");

  hamburger.addEventListener("click", (e) => {
      layers.forEach((layer) => layer.classList.toggle("active"));
      document.getElementById("cc").classList.toggle("move");
      document.getElementById("side").classList.toggle("show");
      document.getElementById("users").classList.toggle("suppress");
  });

  document.querySelector('#image-upload').addEventListener('change', function() {
    var file = this.files[0];
    var formData = new FormData(document.getElementById('change1'));
    formData.append('file', file);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'imgtxt.php');
    xhr.send(formData);
  });
  

  $(document).on('click', 'img#txtimg', function() {
    var $image = $(this);
    var $overlay = $('<div>').addClass('fullscreen');
    var $clone = $image.clone().appendTo($overlay);
    var $closeButton = $('<div>').addClass('close-button').text('❌');
    $closeButton.appendTo($overlay);
    $overlay.appendTo('body');

    $overlay.on('click', function(e) {
      if (e.target === $overlay[0] || $(e.target).is('.close-button')) {
        $overlay.remove();
      }
    });
    
    $clone.on('click', function(e) {
      e.stopPropagation();
    });
  });

