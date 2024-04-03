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
            if (anneeData['annelib'] === annee) {
                console.log( anneeData['semesters'])
                anneeData['semesters'].forEach(semesterData => {
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
                            const promises = [];

                            if (semestre !== 5 || i <= 5) {
                                for (let i = 1; i <= 6; i++) {
                                    promises.push(moyenneComp(semestre, i, etudiantData['modules']));
                                }
                            } else {
                                promises.push(moyenneComp(semestre, 1, etudiantData['modules']));
                                promises.push(moyenneComp(semestre, 2, etudiantData['modules']));
                                promises.push(moyenneComp(semestre, 6, etudiantData['modules']));
                            }

                            Promise.all(promises)
                                .then(results => {
                                    const row = document.createElement('tr');
                                    results.forEach(result => {
                                        const thElement = document.createElement('th');
                                        thElement.textContent = String(result);
                                        row.appendChild(thElement);
                                    });
                                    tBodyElem.appendChild(row);
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
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
            if (anneeData['annelib'] === annee) {
                anneeData['semesters'].forEach(semesterData => {
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

function moyenneComp(semestre, comp, modules) {
    let idComp = semestre * 10 + comp;
    let coeff = 0.00;
    let resultat = 0.00;

    return fetch('http://localhost/SAE4.01/data/compMod.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(jsonData => {
            jsonData.forEach(competence => {
                if (competence['compid'] === idComp) {
                    competence['modules'].forEach(module1 => {
                        modules.forEach(module2 => {
                            if (module1['modId'] === module2['modId']) {
                                resultat += parseFloat(module2['noteVal']) * parseFloat(module1['modVal']);
                                coeff += parseFloat(module1['modVal']);
                            }
                        });
                    });
                }
            });
            resultat = resultat / coeff;
            return resultat;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}