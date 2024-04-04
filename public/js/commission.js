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
        const lienA     = document.createElement('a');
        const semNumber = liens.indexOf(lienLib) + 1; // Sem number 1 to 6

        lienA.setAttribute('href', `commissionComp.php?sem=${semNumber}`);
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

                            row.innerHTML += "<th>Moyenne</th>";

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
                                    results.forEach(result => {
                                        const thElement = document.createElement('th');
                                        thElement.textContent = String(result);
                                        console.log("passé")
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
    let countModule = 0;
    const idModule = []
    const tHeadElem = document.createElement('thead');
    const tBodyElem = document.createElement('tbody');

    const firstRow = document.createElement('tr');
    const row = document.createElement('tr')
    const headers = ["NIP", "Nom", "Prénom", 'Moy', "TP", "TD"]

    headers.forEach(headerText => {
        const thElement = document.createElement('th');
        thElement.setAttribute('rowspan',2)
        thElement.textContent = headerText;
        firstRow.appendChild(thElement);
    });
    console.log("compmod")
    fetch('http://localhost/SAE4.01/data/compMod.json')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(jsonData => {
        jsonData.forEach(bin => {
            if(bin['compid']==semestre*10+competence){
                bin['modules'].forEach(module => {
                    countModule++
                    idModule.push(module['modId'])
                })
                let thElement = document.createElement('th');
                let lienA     = document.createElement('a')
                lienA.setAttribute('href','commission.php')
                lienA.textContent = bin['complib'];
                thElement.setAttribute('colspan',countModule)
                thElement.setAttribute('rowspan',1)
                thElement.appendChild(lienA);
                firstRow.appendChild(thElement);
            }
        })
        jsonData.forEach(comp => {
            if(comp['compid']==semestre*10+competence){
                comp['modules'].forEach(module => {
                    let thElement = document.createElement('th');
                    thElement.textContent = module['modLib']
                    console.log(module['modLib'])
                    row.appendChild(thElement)
                })
            }
        })
    })
    

    tHeadElem.appendChild(firstRow);
    tHeadElem.appendChild(row)

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

                            row.innerHTML += "<th>Moyenne</th>";

                            ['etdgroupetp', 'etdgroupetd'].forEach(key => {
                                const thElement = document.createElement('th');
                                thElement.textContent = etudiantData[key];
                                row.appendChild(thElement);
                            });

                            etudiantData['modules'].forEach(module => {
                                if (idModule.includes(module['modId'])) {
                                    const thElement = document.createElement('th');
                                    thElement.textContent = module['noteVal']
                                    row.appendChild(thElement)
                                }
                            })

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
                                console.log(module2['noteVal'] +" = "+parseFloat(module2['noteVal']))
                                console.log(module1['noteVal'] +" = "+parseFloat(module1['noteVal']))
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