<?php

require_once './private/core/autoload.php';

/**
 * main app file
 */
class XMLReaderJob
{
	private $db = null;

	public function __construct()
	{
		$this->db = new Database();
		$this->setUpTables();
	}

	private function setUpTables()
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS authors (
			id SERIAL PRIMARY KEY,
			name VARCHAR(256) NOT NULL,
			CONSTRAINT unique_name UNIQUE (name)
		);");

		$this->db->query("CREATE TABLE IF NOT EXISTS books (
			id SERIAL PRIMARY KEY,
			author_id INT NOT NULL,
			name VARCHAR(1000) NOT NULL,
			CONSTRAINT unique_author_name UNIQUE (name, author_id)
		);");
	}


	private function upsertAuthorsAndBooksByXML($path)
	{
		foreach ((new Reader($path))->read('books/book') as $node) {
			if ($node->author && $node->name) {
				$queryAuthor = "SELECT * FROM Authors WHERE name = '$node->author' LIMIT 1";
				$currentAuthor = $this->db->query($queryAuthor);
				if (!$currentAuthor) {
					$this->db->query("INSERT INTO authors (name) values('" . $node->author . "');");
					$currentAuthor = $currentAuthor = $this->db->query($queryAuthor);
				}
				$currentAuthor = $currentAuthor[0];
				$this->db->query(
					"INSERT INTO books (author_id, name)
						VALUES(" . $currentAuthor->id . ", '" . $node->name . "')
					ON CONFLICT (author_id, name) DO NOTHING;"
				);
			}
		}
	}

	private function scanXMLFiles($dir)
	{
		$ffs = scandir($dir);
	
		unset($ffs[array_search('.', $ffs, true)]);
		unset($ffs[array_search('..', $ffs, true)]);
	
		if (empty($ffs)) { return; }

		foreach ($ffs as $ff) {
			if (is_dir($dir.'/'.$ff)) {
				$this->scanXMLFiles($dir.'/'.$ff);
			} else {
				$exploded = explode(".", $ff);
				if (count($exploded) > 1 && end($exploded) == 'xml') {
					$this->upsertAuthorsAndBooksByXML($dir . "/" . $ff);
				}
			}
		}
	}

	public function run()
	{
		$this->scanXMLFiles(__DIR__);
	}
}

$job = new XMLReaderJob();
$job->run();
