// Import dependencies
const { insertEtudiant, insertCompetence, insertModule, insertMoyenne } = require('DB.js');
const XLSX = require('xlsx');

/* TEST
const spreadSheet = XLSX.readFile('test.xlsx');
let colums = {};
for (const columName of spreadSheet.SheetNames) 
{
	colums[columName] = XLSX.utils.sheet_to_json(spreadSheet.Sheets[columName]);
}

console.log(JSON.stringify(colums.Sheet1), "\n\n");*/

let fileMoy = document.getElementById('fichier_import');
fileMoy.addEventListener('change', (event) =>
{
	const file = event.target.files[0]; 
	const reader = new FileReader();

	reader.onload = function (event) 
	{
        const data = new Uint8Array(event.target.result);
		const workbook = XLSX.read(data, {type:'array'});
		const sheet = workbook.SheetNames[0];
		const worksheet = workbook.Sheets[sheet];
		const rows = XLSX.utils.sheet_to_json(worksheet);
		const bonus = null;

		// We get all the informations about competences
		const compDetails = rows[0].slice(13, rows[0].length-4);
		for (let i=0; i < compDetails.length; i++)
		{
			let key = compDetails[i];

			if (key.startsWith('Bonus') && is_null(bonus))
			{
				bonus = row[key];
			}

			if (key.startsWith('BIN'))
			{
				const comp  = compDetails[i];
				let lib   = comp;
				let id    = comp.replace('BIN','');
				let semId = comp.replace('BIN','').charAt(0);

				let competence = 
				{
					id   : id,
					lib  : lib,
					semId: semId,
				};
			
				insertCompetence(competence)
				.then(insertedData => {
					console.log('Inserted data:', insertedData);
				})
				.catch(error => {
					console.error('Error:', error);
				});

				let module = 
				{
					//TODO 
					id : ,
					lib: ,
				};
				
				insertModule(module)
				.then(insertedData => {
					console.log('Inserted data:', insertedData);
				})
				.catch(error => {
					console.error('Error:', error);
				});
			}
		}

		for (let row of rows)
		{

			let etudiant = 
			{
				id : row['etudid'],
				civ: row['Civ.'],
				nom: row['Nom'],
				td : row['TD'],
				tp : row['TP'],
				bac: row['Bac'],
				bonus : row[bonus],
				prenom: row['Prénom'],
				parcours: row['Cursus'],
			};

			insertEtudiant(etudiant)
			.then(insertedData => {
				console.log('Inserted data:', insertedData);
			})
			.catch(error => {
				console.error('Error:', error);
			});

			let moyenne = 
			{
				noteVal: ,
				etdId  : ,
				modId  : ,
			};
			
			insertMoyenne(moyenne)
			.then(insertedData => {
				console.log('Inserted data:', insertedData);
			})
			.catch(error => {
				console.error('Error:', error);
			});

		}
	}

	reader.readAsArrayBuffer(file);
});