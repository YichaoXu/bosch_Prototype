function generateTable(row, col) {
    let maxRow = row|5;
    let maxCol = col|5;
    let tmpTable = document.createElement("table");
    let r, c, tmpTr, tmpTd, tmpDIV;
    for (r = 0; r < maxRow; r++) {
        tmpTr = document.createElement("tr");
        for (c = 0; c < maxCol; c++) {
            tmpTd = document.createElement("td");
            tmpDIV = document.createElement("div");
            tmpTd.appendChild(tmpDIV);
            tmpTr.appendChild(tmpTd);
        }
        tmpTable.appendChild(tmpTr);
    }
    return tmpTable;
}
