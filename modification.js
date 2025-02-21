function remove(entry) {
    let row = entry.closest("tr");
    let data = new FormData();
    if(row === null) row = entry.closest("div");   
    if(row.id !== "Vocabulary") {
        let id=row.id.split("_");
        row.remove();

        data.append('section', id[0]);
        data.append('entry', id[1]);
    }
    else {
        data.append('section', "all");
        data.append('entry', "null");
    }
    data.append('vocabulary', document.getElementById("vocabulary").innerHTML);
    fetch("modification.php", {method: "POST", body: data})
    .then((response) => response.json())
    .then((res) => {
        console.log(JSON.stringify(res))
        if(res["success"]) {
            //history.back();
            history.go(1);
        }
    });
}
