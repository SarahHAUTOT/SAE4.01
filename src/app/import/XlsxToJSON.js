const fileMoy = document.getElementById('moy_file');
fileMoy.addEventListener('change', (event) =>
{
	const file = event.target.files[0];
	const reader = new FileReader();

	reader.onload = function (event)
	{
		console.log('file moyenne loaded');

		const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, {type:'array'});
		const sheet = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[sheet];
		const rows = XLSX.utils.sheet_to_json(worksheet, {raw: true});
		let bonus;
		const modules = [];

		// Iterate through the Modules
		const header = Object.keys(rows[0]);
		const compDetails = header.slice(13, header.length-2);
		for (let i=0; i < compDetails.length; i++)
		{
			let key = compDetails[i];

			let isBonus = key.startsWith('Bonus'); 
			let isComp  = !isNaN(parseInt(key.replace('BIN','')));
			let isMod   = !isComp && !isBonus; 

			if (isBonus)
			{
				bonus = key;
				continue;
			}
			
			if (isMod)
			{
				let lib = compDetails[i];
				let id = compDetails[i].slice(4); // getting rid of the 'BINR'
				let module  = { id: id, lib: lib };
				modules.push(module);
			}
		}
		
		// Iterate through the Competences and its modules 
		for (let row of rows)
		{
			let etudiant = 
			{
				id : row['etudid'],
				civ: row['Civ.'],
				abs: row['Abs'],
				nom: row['Nom'],
				td : row['TD'],
				tp : row['TP'],
				bac: row['Bac'],
				bonus : row[bonus],
				prenom: row['Prénom'], // TODO problème d'encodage => prenom undefined
				parcours: row['Cursus'],
			};

			// TODO Insertion Etudiant
			console.log(etudiant);

			for (let mod of modules)
			{
				let moyenne = { noteVal: row[mod.lib], etdId  : row['etudid'], modId  : mod.id };

				// TODO  Insertion moyenne
				console.log(moyenne);
			}
		}
	};

	reader.readAsArrayBuffer(file);
}, false);

/*
const fileCoef = document.getElementById('coef_file');
fileCoef.addEventListener('change', (event) =>
{
	const file = event.target.files[0];
	const reader = new FileReader();

	reader.onload = function (event)
	{
		console.log('file coef loaded');

		const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, {type:'array'});
		const sheet = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[sheet];
		const rows = XLSX.utils.sheet_to_json(worksheet, {raw: true});

		// Iterate through the Competences and its modules 
		for (let row of rows)
		{
			console.log(row);
		}
	};

	reader.readAsArrayBuffer(file);
});
*/

const fileCoef = document.getElementById('coef_file');
fileCoef.addEventListener('change', (event) =>
{
	const file = event.target.files[0];

	console.log('file loaded');
	console.log(file.name);

	const reader = new FileReader();
	reader.onload = function (event)
	{
		const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, {type:'array'});
		const sheet = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[sheet];

		// Initialize an empty JSON object
		const jsonData = {};

		for (const cell in worksheet)
		{
			// Extract the column name (key)
			const key = cell.replace(/[0-9]/g, '');
			console.log(cell);
			console.log(key);

			// If the key doesn't exist in the JSON object, create it
			if (!jsonData[key])
			{
				jsonData[key] = [];
			}

			// Push the value of the current cell to the corresponding key
			jsonData[key].push(worksheet[cell].v);
		}

		// Convert the JSON object to an array of objects
		const dataArray = Object.keys(jsonData).map(key =>
			{
				const obj = {};
				obj[key] = jsonData[key];
				return obj;
			}
		);

		console.log(jsonData);
	};
	reader.readAsArrayBuffer(file);
}, false);