function generationCommission(table, annee, semestre) {
    
    
    while (table.hasChildNodes()) {
        table.removeChild(table.firstChild);
    }

    const tHeadElem = document.createElement('thead');
    const tBodyElem = document.createElement('tbody');

    const firstRow = document.createElement('tr');
    const headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"]
    const liens   = ["C1", "C2"];

    if (semestre != 5) {
        liens.push("C3", "C4", "C5");
    }
    liens.push("C6");

    headers.forEach(headerText => {
        const thElement = document.createElement('th');
        thElement.textContent = headerText;
        firstRow.appendChild(thElement);
    });

    liens.forEach(lienLib => {
        const thElement = document.createElement('th');
        const lienA     = document.createElement('a')
        lienA.setAttribute('href','commissionComp.php')
        lienA.textContent = lienLib;
        thElement.appendChild(lienA);
        firstRow.appendChild(thElement);
    });

    tHeadElem.appendChild(firstRow);
    table.appendChild(tHeadElem);
    table.appendChild(tBodyElem);


    fetch('http://localhost/SAE4.01/data/donnees.json')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(jsonData => {
        jsonData.forEach(anneeData => {
            console.log(anneeData['annelib'])
            console.log(anneeData['annelib'] === '2021-2022')
            if (anneeData['annelib'] === '2021-2022') {
                anneeData['semesters'].forEach(semesterData => {
                    console.log(anneeData['semesters'])
                    console.log(semesterData['semid'] === semestre)
                    if (semesterData['semid'] === semestre) {
                        semesterData['etd'].forEach(etudiantData => {
                            const row = document.createElement('tr');
                            
                            ['etdid', 'etdnom', 'etdprenom'].forEach(key => {
                                const thElement = document.createElement('th');
                                thElement.textContent = etudiantData[key];
                                row.appendChild(thElement);
                            });

                            row.innerHTML += "<th>moyenne</th>";

                            ['etdgroupetp', 'etdgroupetd'].forEach(key => {
                                const thElement = document.createElement('th');
                                thElement.textContent = etudiantData[key];
                                row.appendChild(thElement);
                            });

                            for (let i = 1; i <= 6; i++) {
                                const thElement = document.createElement('th');
                                if (semestre !== 5 || i <= 5) {
                                    thElement.textContent = i;
                                    row.appendChild(thElement);
                                }
                            }

                            tBodyElem.appendChild(row);
                        });
                    }
                });
            }
        });
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}
function generationCommissionComp(table, annee, semestre,competence) {
    
    
    while (table.hasChildNodes()) {
        table.removeChild(table.firstChild);
    }

    const tHeadElem = document.createElement('thead');
    const tBodyElem = document.createElement('tbody');

    const firstRow = document.createElement('tr');
    const headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"]

    headers.forEach(headerText => {
        const thElement = document.createElement('th');
        thElement.textContent = headerText;
        firstRow.appendChild(thElement);
    });
    fetch('http://localhost/SAE4.01/data/comp.json')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(jsonData => {
        let countCompetence =0;
        jsonData.forEach(bin => {
            if(bin['compid']>=competence*10+1 && bin['compid']<=competence*10+9){countCompetence++}
        })
        const thElement = document.createElement('th');
        const lienA     = document.createElement('a')
        lienA.setAttribute('href','commission.php')
        lienA.textContent = 'C'+competence;
        thElement.setAttribute('colspan',countCompetence)
        thElement.appendChild(lienA);
        firstRow.appendChild(thElement);
    })
    

    tHeadElem.appendChild(firstRow);
    table.appendChild(tHeadElem);
    table.appendChild(tBodyElem);


    fetch('http://localhost/SAE4.01/data/donnees.json')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(jsonData => {
        jsonData.forEach(anneeData => {
            console.log(anneeData['annelib'])
            console.log(anneeData['annelib'] === '2021-2022')
            if (anneeData['annelib'] === '2021-2022') {
                anneeData['semesters'].forEach(semesterData => {
                    console.log(anneeData['semesters'])
                    console.log(semesterData['semid'] === semestre)
                    if (semesterData['semid'] === semestre) {
                        semesterData['etd'].forEach(etudiantData => {
                            const row = document.createElement('tr');
                            
                            ['etdid', 'etdnom', 'etdprenom'].forEach(key => {
                                const thElement = document.createElement('th');
                                thElement.textContent = etudiantData[key];
                                row.appendChild(thElement);
                            });

                            row.innerHTML += "<th>moyenne</th>";

                            ['etdgroupetp', 'etdgroupetd'].forEach(key => {
                                const thElement = document.createElement('th');
                                thElement.textContent = etudiantData[key];
                                row.appendChild(thElement);
                            });

                            for (let i = 1; i <= 6; i++) {
                                const thElement = document.createElement('th');
                                if (semestre !== 5 || i <= 5) {
                                    thElement.textContent = i;
                                    row.appendChild(thElement);
                                }
                            }

                            tBodyElem.appendChild(row);
                        });
                    }
                });
            }
        });
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}