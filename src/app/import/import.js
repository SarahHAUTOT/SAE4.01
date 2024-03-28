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

        // Prepare data to send to PHP script
        const students = rows.map(row => ({
            id: row['code_nip'],
            civ: row['Civ.'],
            abs: parseInt(row['Abs'] - row['Just.']),
            nom: row['Nom'],
            td: row['TD'],
            tp: row['TP'],
            bac: row['Bac'],
            bonus: row[bonus],
            prenom: row['Pr\u00E9nom'], // Use bracket notation for 'Prénom'
            parcours: row['Cursus'],
        }));

		// Send data to PHP script using fetch
		fetch('test.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ students }),
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
    };

    reader.readAsArrayBuffer(file);
}, false);






const fileCoef = document.getElementById('coef_file');
fileCoef.addEventListener('change', (event) =>
{
	const file = event.target.files[0];

	console.log('file loaded');

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
		
		console.log(competences);

		// Insertions competences
		let modAttr = ['code', 'lib'];
		for ( let nbSem = 1; nbSem < workbook.SheetNames.length -1; nbSem++)
		{
			sheet     = workbook.SheetNames[nbSem];
			worksheet = workbook.Sheets[sheet];

			let mod     = {};
			let compMod = {};

			let i = 0;
			let compColumn = null;
			for (const cell in worksheet)
			{
				let dataCell = worksheet[cell].v;
				let info = i > parseInt(workbook.SheetNames.length -2); 
				let moduleInfo = info && i - parseInt(workbook.SheetNames.length) < modAttr.length;
				

				if (cell === '!ref') continue;
				
				
				if (moduleInfo)
				{
					let index = i - parseInt(workbook.SheetNames.length -1);
					mod[modAttr[index]] = dataCell;
					console.log(mod);
				}
				
				if (info)
				{
					compColumn = (i - parseInt(workbook.SheetNames.length) === 3) ? cell : compColumn;
					//compMod = { modId: }

				}

				console.log(info);
				console.log(i - parseInt(workbook.SheetNames.length -1) < modAttr.length);
				console.log( sheet + ' ' + cell + ' : ' + dataCell)

				compMods.push(compMod);
				i++;
			}

		}

		console.log(modules);

	};

	reader.readAsArrayBuffer(file);
}, false);