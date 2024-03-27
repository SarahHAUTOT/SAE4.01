const DB = require('pg');

// Connection configuration
const db = new DB(
	{
		user: 'na222180',
		host: 'localhost',
		database: 'na222180',
		password: 'DUCAL873',
		port: 5432,
	});

async function insertEtudiant(etd)
{
	const client = await db.connect();
	try {
		const query = 'INSERT INTO Etudiant VALUES($1, $2, $3, $4, $5, $6, $7, $8, $9) RETURNING *';
		const values = [etd.id, etd.civ, etd.nom, etd.prenom, etd.tp, etd.td, etd.parcours, etd.bonus, etd.bac];
		const result = await client.query(query, values);
		console.log('Data inserted in Etudiant successfully', result.rows[0]);
	}
	catch (error)
	{
		console.error('Error inserting Etudiant:', error);
		throw error;
	}
	finally
	{
		client.release();
	}
}

async function insertMoyenne(moy)
{
	const client = await db.connect();
	try {
		const query = 'INSERT INTO Moyenne VALUES($1, $2, $3) RETURNING *';
		const values = [moy.noteVal, moy.etdId, moy.modId]
		const result = await client.query(query, values);
		console.log('Data inserted in Moyenne successfully', result.rows[0]);
	}
	catch (error)
	{
		console.error('Error inserting Moyenne:', error);
		throw error;
	}
	finally
	{
		client.release();
	}
}

async function insertModule(mod)
{
	const client = await db.connect();
	try {
		const query = 'INSERT INTO Module VALUES($1, $2) RETURNING *';
		const values = [mod.id, moy.lib]
		const result = await client.query(query, values);
		console.log('Data inserted in Module successfully', result.rows[0]);
	}
	catch (error)
	{
		console.error('Error inserting Module:', error);
		throw error;
	}
	finally
	{
		client.release();
	}
}

async function insertCompetence(comp)
{
	const client = await db.connect();
	try {
		const query = 'INSERT INTO Competence VALUES($1, $2, $3) RETURNING *';
		const values = [comp.id, comp.lib, comp.semId]
		const result = await client.query(query, values);
		console.log('Data inserted in Competence successfully', result.rows[0]);
	}
	catch (error)
	{
		console.error('Error inserting Competence:', error);
		throw error;
	}
	finally
	{
		client.release();
	}
}

module.exports = insertEtudiant;
module.exports = insertModule;
module.exports = insertMoyenne;
module.exports = insertCompetence;