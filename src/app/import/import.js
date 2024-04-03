const mybtn = document.getElementById('save');
mybtn.addEventListener('click', generateAll, false);

const btnJurys = document.querySelectorAll('.jury');
btnJurys.forEach(function(btn) {
    btn.addEventListener('change', decomposeJury);
});

const btnMoyennes = document.querySelectorAll('.moyenne');
btnMoyennes.forEach(function(btn) {
    btn.addEventListener('change', decomposeMoyennes);
});

const btnCoef = document.querySelectorAll('.coef');
btnCoef.forEach(function(btn) {
    btn.addEventListener('change', decomposeCoef);
});


let modules = [];
let students = [];
let moyennes = [];
let competences = [];
let compMods = [];
let admComp = [];


function isEmpty(value)
{
	return (value == null || (typeof value === "string" && value.trim().length === 0));
}

function callPHP(file, action, datas) {

	return new Promise((resolve, reject) => {
		fetch(file, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({ action: action, datas }),
			})
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.text();
			})
			.then(data => {
				resolve(data); // Renvoie le message de succès du script PHP
			})
			.catch(error => {
				reject(error);
			});
	});
}


function checkYearFormat(annee) {
    // Expression régulière pour vérifier le format "YYYY-YYYY" avec vérification de l'ordre des années
    let regex = /^(\d{4})-(\d{4})$/;
    
    // Extraction des deux années
    let match = annee.match(regex);
    if (!match) {
        return false; // Le format est incorrect
    }
    let premiereAnnee = parseInt(match[1]); // Première année
    let deuxiemeAnnee = parseInt(match[2]); // Deuxième année

    // Vérification si l'année est vide ou ne correspond pas au format attendu
    if (annee === "" || isNaN(premiereAnnee) || isNaN(deuxiemeAnnee) || deuxiemeAnnee !== premiereAnnee + 1) {
        return false;
    } else {
        return true;
    }
}

function generateAll()
{

	let cheminFic = "../src/app/DB.inc.php";
	let cheminJSON = "../src/app/DBtoJSON.php";
	anneLib = document.getElementById('anneeLib').value;

	if (!checkYearFormat(anneLib))
	{
		alert("Veuillez entré une année dans un format correct");
		return 0;
	}


	// Insertion des étudiants
	callPHP(cheminFic, 'insertAnnee', anneLib)
		.then(() => {
			// Après l'insertion des étudiants, insérer les modules
			return callPHP(cheminFic, 'insertStudents', students);
		})
		.then(() => {
			// Après l'insertion des étudiants, insérer les modules
			return callPHP(cheminFic, 'insertModules', modules);
		})
		.then(() => {
			// Après l'insertion des modules, insérer les compétences
			return callPHP(cheminFic, 'insertCompetences', competences);
		})
		.then(() => {
			// Après l'insertion des compétences, insérer les moyennes
			return callPHP(cheminFic, 'insertMoyennes', moyennes);
		})
		.then(() => {
			// Enfin, insérer compMods après l'insertion des moyennes
			return callPHP(cheminFic, 'insertCompMods', compMods);
		})
		.then(() => {
			return callPHP(cheminFic, 'insertAdmComps', admComp);
		})
		.then(() => {
			// Toutes les opérations ont réussi
			console.log("Toutes les données ont été insérées avec succès !");
		})
		.then(() => {
			fetch(cheminJSON)
			.then(response => {
				if (!response.ok) {
				throw new Error('Network response was not ok');
				}
				return response.text();
			})
			.then(data => {
				console.log(data); // Affiche la réponse du fichier PHP
			})
			.catch(error => {
				console.error('Une erreur s\'est produite lors de l\'appel PHP:', error);
			});
		})
		.catch(error => {
			console.error('Une erreur s\'est produite lors de l\'appel PHP:', error);
		});



	// window.location.href = "accueilAdmin.php";
}


function decomposeMoyennes(event)
{
	const file = event.target.files[0];
	const reader = new FileReader();

	reader.onload = function (event)
	{
		console.log('file moyenne loaded');

		const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, { type: 'array' });
		const sheet = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[sheet];
		const rows = XLSX.utils.sheet_to_json(worksheet, { raw: true });
		let bonus;

		// Iterate through the Modules
		const header = Object.keys(rows[0]);
		const compDetails = header.slice(13, header.length -2);
		for (let i = 0; i < compDetails.length; i++) {
			let key = compDetails[i];

			let isBonus = key.startsWith('Bonus');
			let isComp = !isNaN(parseInt(key.replace('BIN', '')));
			let isMod = !isComp && !isBonus;

			if (isBonus)
			{
				bonus = key;
				continue;
			}

			if (isMod)
			{
				let lib = compDetails[i];
				let id  = compDetails[i].replace('BIN', ''); // getting rid of the 'BIN'
				let mod = { id: id, lib: lib };
				modules.push(mod);
			}
		}

		// Iterate through the Competences and its modules
		
		for (let row of rows)
		{
			let student = 
			{
				id : row['code_nip'],
				civ: row['Civ.'],
				abs: parseInt(row['Abs'] - row['Just.']),
				nom: row['Nom'],
				td : row['TD'],
				tp : row['TP'],
				bac: row['Bac'],
				bonus : isNaN(row[bonus]) ? 0 : row[bonus],
				prenom: row['Pr\u00E9nom'], 
				cursus: row['Cursus'],
			};

			students.push(student)

			for (let mod of modules)
				if (!isNaN(row[mod.lib]))
				{
					let moy =
					{
						etdId: row['code_nip'],
						moy  : row[mod.lib],
						modId: mod.id
					};
					
				}
		}
	};

	reader.readAsArrayBuffer(file);
}


function decomposeCoef(event)
{
	const file = event.target.files[0];

	const reader = new FileReader();
	reader.onload = function (event)
	{
		console.log('file coef loaded');

		const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, {type:'array'});
		let sheet = workbook.SheetNames[0];
		let worksheet = workbook.Sheets[sheet];

		// Initialize an empty JSON object
		const jsonData = {};
		
		for (const cell in worksheet)
			if ( cell !== '!ref' )
			{
				let dataCell = worksheet[cell].v;
				
				for (let nbSem=1; nbSem <= workbook.SheetNames.length -1; nbSem++)
				{
					let compId = cell.substring(1, cell.length);
					let comp = 
					{
						compLib: dataCell.trim(),
						compId : nbSem+''+compId,
						semId  : nbSem,
						compCode: 'BIN'+nbSem+''+compId,
					};

					competences.push(comp);
				}
				
			}
		
		let nbComp = workbook.SheetNames.length -1;

		let modAttr = ['modCode', 'modLib', 'modCat'];
		for ( let nbSem = 1; nbSem < workbook.SheetNames.length -1; nbSem++)
		{
			sheet     = workbook.SheetNames[nbSem];
			worksheet = workbook.Sheets[sheet];

			let mod     = {};
			let compMod = {};

			let i = 0;
			let currentModId = 0;

			for (const cell in worksheet)
			{
				const matches = cell.match(/([A-Z]+)([0-9]+)/);
				if (cell === '!ref' || matches === null) continue;

				let dataCell = worksheet[cell].v;
				let letter   = matches[1]; 
				let idColumn = letter.charCodeAt(0) - 'A'.charCodeAt(0);

				let isInfo       = i > parseInt(workbook.SheetNames.length -1); 
				let isModuleInfo = isInfo && idColumn < modAttr.length;

				if (isModuleInfo)
				{
					mod[modAttr[idColumn]] = dataCell;

					if (Object.keys(mod).length === modAttr.length)
					{
						mod['modId' ] = (mod.modCode).replace('BIN','');
						currentModId = mod.modId;

						modules.push(mod);
						mod = {};
					}

				}

				if (isInfo && !isModuleInfo && !isEmpty(dataCell))
				{
					let compColumn = 'D'.charCodeAt(0);
					let idComp = (letter.charCodeAt(0) - compColumn) + ((nbSem -1) * nbComp);
					
					let compId = competences[idComp].compId; // TODO wrong thing
					compMod = { compId: compId, modId: currentModId, modCoef: dataCell };

					compMods.push(compMod);
					compMod = {};
				}

				i++;
			}
		}
	};

	reader.readAsArrayBuffer(file);
}





function decomposeJury ()
{
	
	const file = event.target.files[0];
	if (!file) return;

	const reader = new FileReader();
	reader.onload = function (event)
	{
		console.log('file jury loaded');

		const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, { type: 'array' });
		const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
		const excelData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

		//On cherche ou sont les ressources
		let ind = [];
		let pattern = /^BIN\d{2}$/;


		excelData[1]; //header

		for (let i = 0; i < excelData[0].length; i++)
			if (pattern.test(excelData[0][i]))
				ind.push(i+1);

		// Parcourir chaque ligne
		for (let i = 1; i < excelData.length; i++) {
			//Récuperer la ligne
			const rowData = excelData[i];

			//ID Etd
			const etdid       = rowData[1];


			//pour chaque compétence
			ind.forEach(function (e) {
				let admCompBis = 
				{
					'etdId' : etdid,
					'adm'   : excelData[i][e],
					'comp'  : excelData[i][ind - 1],
				}
				admComp.push(admCompBis);
			});
		}
	};
	reader.readAsArrayBuffer(file);
}