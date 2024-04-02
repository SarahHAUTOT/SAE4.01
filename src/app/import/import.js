const mybtn = document.getElementById('generate');
mybtn.addEventListener('click', generateAll, false);


let modules = [];
let students = [];
let moyennes = [];
let competences = [];
let compMods = [];


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




function generateAll()
{
	$anneLib = document.getElementById('anneeLib').value;

	console.log("Etudiants", students);
	console.log("Moyenne", moyennes);
	console.log("Competences", competences);
	console.log("Modules", modules);
	console.log("CompMod", compMods);

	// Insertion des étudiants
	callPHP('../DB.inc.php', 'insertAnnee', $anneLib)
		.then(() => {
			// Après l'insertion des étudiants, insérer les modules
			return callPHP('../DB.inc.php', 'insertStudents', students);
		})
		.then(() => {
			// Après l'insertion des étudiants, insérer les modules
			return callPHP('../DB.inc.php', 'insertModules', modules);
		})
		.then(() => {
			// Après l'insertion des modules, insérer les compétences
			return callPHP('../DB.inc.php', 'insertCompetences', competences);
		})
		.then(() => {
			// Après l'insertion des compétences, insérer les moyennes
			return callPHP('../DB.inc.php', 'insertMoyennes', moyennes);
		})
		.then(() => {
			// Enfin, insérer compMods après l'insertion des moyennes
			return callPHP('../DB.inc.php', 'insertCompMods', compMods);
		})
		.then(() => {
			// Toutes les opérations ont réussi
			console.log("Toutes les données ont été insérées avec succès !");
		})
		.catch(error => {
			console.error('Une erreur s\'est produite lors de l\'appel PHP:', error);
		});
}


function decomposeMoyennes(event)
{
	const file = event.target.files[0];
	const reader = new FileReader();

	reader.onload = function (event) {
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

	console.log('file coef loaded');

	const reader = new FileReader();
	reader.onload = function (event)
	{
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

		console.log(modules);
		console.log(competences);
		console.log(compMods);
	};

	reader.readAsArrayBuffer(file);
}