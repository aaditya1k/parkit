(function() {
  if (document.getElementById('admin-parking-form')) {
    parkingForm();
  }
  if (document.getElementById('admin-parking-level-form')) {
    gridGenerator();
  }
  showValueButtons();
})();

function parkingForm() {
  const chargeMethods = document.getElementsByClassName('charge-method');
  for (let i = 0; i < chargeMethods.length; i++) {
    chargeMethods[i].addEventListener("click", function() {
      const type = this.getAttribute("data-type");
      const chargeMethodPerHour = document.getElementsByClassName(type + '-charge-method-per-hour')[0];
      const chargeMethodCat = document.getElementsByClassName(type + '-charge-method-in-category')[0];
      if (this.value == "1") {
        chargeMethodPerHour.style.display = "block";
        chargeMethodCat.style.display = "none";
      } else if (this.value == "2") {
        chargeMethodPerHour.style.display = "none";
        chargeMethodCat.style.display = "block";
      }
    }, false);
  }
}

function showValueButtons() {
  const hide = "****";
  const showValues = document.getElementsByClassName('show-value');
  const showValuesMethod = function() {
    if (this.classList.contains("active")) {
      this.classList.remove("active");
      this.innerHTML = hide;
    } else {
      this.classList.add("active");
      this.innerHTML = this.getAttribute("data-value");
    }
  };
  for (let i = 0; i < showValues.length; i++) {
    showValues[i].innerHTML = hide;
    showValues[i].addEventListener("click", showValuesMethod, false);
  }
}

function gridGenerator() {
  const emptyCell = "(empty)";
  const map = {
    tools: [
      {icon: 'fa-long-arrow-up', title: "Up"},
      {icon: 'fa-long-arrow-down', title: "Down"},
      {icon: 'fa-long-arrow-left', title: "Left"},
      {icon: 'fa-long-arrow-right', title: "Right"},
      {icon: 'fa-car', title: "Cars"},
      {icon: 'fa-motorcycle', title: "Bikes"},
      {icon: 'fa-sign-in', title: "Entry"},
      {icon: 'fa-sign-out', title: "Exit"},
      {icon: 'fa-stop', title: "Block / Wall"},
      {icon: 'fa-id-card-o', title: "Booth"},
      {icon: '', title: emptyCell},
    ],
    selected: {
      index: null,
      tool: null
    }
  };

  const generateTools = function (toolsId) {
    let toolsHtml = '<div class="gen-tools">Tools</div>';
    for (let i = 0; i < map.tools.length; i++) {
      const icon = map.tools[i].icon;
      const title = map.tools[i].title;
      if (i === 0) {
        map.selected.index = "0";
        map.selected.tool = map.tools[i];
      }
      toolsHtml += '<div class="gen-m-cols'+(i === 0 ? ' selected' : '')+'" data-index="'+i+'"'
                 + ' title="'+title+'" data-icon="'+icon+'"><i class="fa '+icon+'" aria-hidden="true"></i></div>';
    }
    document.getElementById(toolsId).innerHTML = toolsHtml;

    $("#" + toolsId + " .gen-m-cols").click(function() {
      const $this = $(this);
      const selected = document.querySelector("#" + toolsId + " .gen-m-cols.selected");
      if (selected) {
        selected.classList.remove("selected");
      }
      $this.addClass("selected");
      map.selected.index = $this.data("index");
      map.selected.tool = map.tools[parseInt(map.selected.index)];
    });
  }

  const generateBlocks = function (dom, rows, cols, mapIndexs) {
    let html = "";
    let totalI = 0;
    const canFill = (typeof mapIndexs != "undefined");
    for (let i = 0; i < rows; i++) {
      html += '<div class="gen-m-rows">';
      for (let j = 0; j < cols; j++) {
        html += '<div class="gen-m-cols"'+(canFill && mapIndexs[totalI] != null ? 'title="'+map.tools[mapIndexs[totalI]].title+'" data-index="'+mapIndexs[totalI]+'"' : 'title="'+emptyCell+'"')+'>';

        if (canFill && mapIndexs[totalI] != null) {
          html += '<i class="fa '+map.tools[ mapIndexs[totalI] ].icon+'" aria-hidden="true"></i>';
        }

        html += '</div>';
        ++totalI;
      }
      html += '</div>';
    }
    dom.innerHTML = html;
    dom.style.display = "block";

    $(".gen-m-rows .gen-m-cols").click(function() {
      const $this = $(this);
      $this.attr("data-index", map.selected.index);
      $this.attr("title", map.selected.tool.title);
      if (map.selected.index === "10") {
        $this.html('');
      } else {
        $this.html('<i class="fa '+map.selected.tool.icon+'" aria-hidden="true"></i>');
      }
    });
  }

  function getGrid(domId) {
    const dom = document.getElementById(domId);
    const mapindex = [];
    let rows = dom.querySelectorAll('.gen-m-rows').length;
    let cols = dom.querySelectorAll('.gen-m-rows .gen-m-cols');

    cols.forEach(function (i) {
      if (i.getAttribute("data-index") === "10") {
        mapindex.push(null);
      } else {
        mapindex.push(i.getAttribute("data-index"));
      }
    });
    return {
      rows: rows,
      cols: cols.length / rows,
      map: mapindex
    };
  }

  generateTools('gen-map-tools');

  document.getElementById("grid-regenrate").addEventListener("click", function(e) {
    e.preventDefault();
    const rowDom = document.getElementById("grid-row");
    const colDom = document.getElementById("grid-col");
    rowDom.classList.remove("error");
    colDom.classList.remove("error");
    const rows = parseInt(rowDom.value);
    const cols = parseInt(colDom.value);
    if (isNaN(rows) || rows < 1) {
      rowDom.classList.add("error");
    }
    if (isNaN(cols) || cols < 1) {
      colDom.classList.add("error");
    }
    generateBlocks(
      document.getElementById("gen-map-tiles"),
      rows,
      cols
    );
  });

  document.getElementById("grid-input").addEventListener("change", function() {
    const error = "Your Grid console string is corrupted.";
    let mapped;
    try {
      mapped = JSON.parse(this.value);
    } catch (err) {
      alert(error);
    }
    if (mapped.rows && mapped.cols && mapped.map) {
      generateBlocks(
        document.getElementById("gen-map-tiles"),
        mapped.rows,
        mapped.cols,
        mapped.map
      );
      document.getElementById("grid-row").value = mapped.rows;
      document.getElementById("grid-col").value = mapped.cols;
    } else {
      alert(error);
    }
  });

  document.getElementById("grid-save-new").addEventListener("click", function(e) {
    e.preventDefault();
    console.log(JSON.stringify(getGrid("gen-map-tiles")));
    document.getElementById("grid-input").value = JSON.stringify(getGrid("gen-map-tiles"));
  });
}