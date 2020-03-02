window.onload = function () {

  /**
   * Handler form.
   */
  function ajaxRequestHandler() {
    let form = document.getElementById("sklForm");

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      let request = new XMLHttpRequest();
      let formData = new FormData(form);

      request.open("POST", "/ajax.php");
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {

          /**@let file - full name of the uploaded file. */
          let file = document.getElementById("skl_file").files[0].name;

          let fileType = file.substr(-4);
          let extensions = [".jpg", ".png"];

          if (!extensions.includes(fileType)) {
            alert("Invalid file type!");
            return;
          }

          form.reset();

          setTimeout(function () {
            window.location.reload()
          }, 1000);
        }
      };

      formData.append("action", "create-comment");

      request.send(formData);
    });
  }

  /**
   * Handler like/dislike.
   */
  function ajaxLikeIncrement() {
    let like = document.getElementsByClassName("skl-comment__like");

    for (let i = 0; i < like.length; i++) {
      like[i].addEventListener("click", function (event) {

        incrementIndex(event.currentTarget);

        let commentId = event.currentTarget.getAttribute("data-id");
        let request = new XMLHttpRequest();
        let data = new FormData();

        request.open("POST", "/ajax.php");

        data.append("action", "increment-like");
        data.append("commentId", commentId);

        request.send(data);
      });
    }
  }

  function incrementIndex(elem) {
    let count = elem.querySelector("span");
    count.innerText = parseInt(count.innerText) + 1;
  }

  ajaxRequestHandler();
  ajaxLikeIncrement();
};