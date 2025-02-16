function remove(entry) {
    let row = entry.closest("tr");
    let id=row.id.split("_");
    row.remove();

    let data = new FormData();
    data.append('vocabulary', document.getElementById("vocabulary").innerHTML);
    data.append('section', id[0]);
    data.append('entry', id[1]);
    fetch("modification.php", {method: "POST", body: data})
    .then((response) => response.json())
    .then((res) => console.log(JSON.stringify(res)));
}
