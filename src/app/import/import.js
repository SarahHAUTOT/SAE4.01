const fileMoy = document.getElementById('moy_file');
fileMoy.addEventListener('change', (event) => {
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
        const modules = [];

        // Iterate through the Modules
        const header = Object.keys(rows[0]);
        const compDetails = header.slice(13, header.length - 2);
        for (let i = 0; i < compDetails.length; i++) {
            let key = compDetails[i];

            let isBonus = key.startsWith('Bonus');
            let isComp = !isNaN(parseInt(key.replace('BIN', '')));
            let isMod = !isComp && !isBonus;

            if (isBonus) {
                bonus = key;
                continue;
            }

            if (isMod) {
                let lib = compDetails[i];
                let id = compDetails[i].slice(4); // getting rid of the 'BINR'
                let module = { id: id, lib: lib };
                modules.push(module);
            }
        }


		let students = [];
		let moyennes = [];

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
				parcours: row['Cursus'],
			};

			// TODO Insertion Etudiant
			students.push(student)

			for (let mod of modules)
			{
				let moy =
				{
					moy  : isNaN(row[mod.lib]) ? 0 : row[mod.lib],
					etdId: row['code_nip'],
					modId: mod.id
				};

				// TODO  Insertion moyenne
				moyennes.push(moy);
			}
		}

		// Send data to PHP script using fetch
		callPHP('../DB.inc.php', 'insertStudents', students);
		callPHP('../DB.inc.php', 'insertMoyennes', moyennes);
    };

    reader.readAsArrayBuffer(file);
}, false);




const fileCoef = document.getElementById('coef_file');
fileCoef.addEventListener('change', (event) =>
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
		
		const competences = [];
		const modules  = [];
		const compMods = [];
		
		for (const cell in worksheet)
			if ( cell !== '!ref' )
			{
				let dataCell = worksheet[cell].v;
				let comp = { lib: dataCell.trim(), id:cell.substring(1, cell.length) };
				competences.push(comp);
			}
		

		// Insertions competences
		let modAttr = ['code', 'lib'];
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

				let info       = i > parseInt(workbook.SheetNames.length -2); 
				let moduleInfo = info && idColumn < modAttr.length;

				
				if (moduleInfo)
				{
					mod[modAttr[idColumn]] = dataCell;

					if (Object.keys(mod).length === 2)
					{
						mod['id'] = (mod.code).slice(4);
						currentModId = mod.id;
						modules.push(mod);
						mod = {};
					}
				}

				if (info && !moduleInfo && !isEmpty(dataCell))
				{
					let compColumn = 'C'.charCodeAt(0);
					let idComp = letter.charCodeAt(0) - compColumn;

					
					let compId = nbSem + '' + competences[idComp].id;
					compMod = { compId: compId, modId: currentModId, modCoef: dataCell };

					compMods.push(compMod);
					compMod = {};
				}

				i++;
			}

		}

		callPHP('../DB.inc.php', 'insertCompetences', competences);
		callPHP('../DB.inc.php', 'insertModules'    , modules);
		callPHP('../DB.inc.php', 'insertCompMods'   , compMods);
	};

	reader.readAsArrayBuffer(file);
	
}, false);

function isEmpty(value)
{
	return (value == null || (typeof value === "string" && value.trim().length === 0));
}






function callPHP (file, action, datas)
{
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
			console.log(data); // Success message from PHP script
		})
		.catch(error => {
			console.error('There was a problem with the fetch operation:', error);
		});
}
