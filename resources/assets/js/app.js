if (document.getElementById("demo_routes")) {
  const groupedTitleDoms = document.querySelectorAll(".route .method");
  for (const groupedTitleDom of groupedTitleDoms) {
    groupedTitleDom.addEventListener("click", function () {
      const dropdownDom = this.parentNode.parentNode.querySelector(".route-dropdown");
      dropdownDom.classList.toggle("show");
    });
  }
}