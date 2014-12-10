<?php

class autoSync extends autoScripts {
	// This is a command line script only.

	private $jLimit = 50; // Job limit per run.

	public function exec() {
		echo "Data Sync Task\n-------------\n";

		$sql = $this->db;

		if( $sql->countJobs( 'sync' ) === 0 ) {
			echo "No jobs, script is exiting.\n";
			die();
		} else {
			$jobs = $sql->getJobs( 'sync', $this->jLimit );

			foreach ( $jobs as $job ) {
				$this->processJob( $job );
			}
		}

		echo "Sync completed.\n";
	}

	private function processJob ( $job ) {
		$fileRecord = new RecordFile();
		$dataSources = $fileRecord->getDataSources( $job->job_filmId );

		if( $dataSources === 0 ) {
			// Get data from list of global sources if can be found
			$this->attemptFindFromGlobal( $job );
		} else {
			foreach( $dataSources as $source ) {
				if( array_key_exists( $source, $this->config->validDataSources ) ) {
					$find = $this->dataFind( $source );
					if ( $find === true ) {
						break;
					}
				}
			}
		}
		if( $data == NULL ) {
			// Notify the admin that the sources that are supposed to have the data... don't.
			$this->flagAdmin(
				array(
					'jobId' => $job->job_jobId,
					'jobFilm' => $job->job_filmId
				),
				1 // Status code, 1 = verified sources do not contain this info.
			);
			// Fallback to system preferred choices.
			$this->attemptFindFromGlobal( $job );
		}
	}

	private function attemptFindFromGlobal( $job ) {
		foreach ( $this->config->systemSupportedSources as $source ) {
			$find = $this->dataFind( $source );
			if ( $find === true ) {
				break;
			}
		}
		if ( $data = NULL ) {
			// Notify the admin that the sources that are supposed to have the data... don't.
			$this->flagAdmin(
				array( 'jobId' => $job->job_jobId, 'jobFilm' => $job->job_filmId ),
				2 // Status code, 2 = preferred sources do not contain this info.
			);
		}
	}


	private function dataFind( $source ) {
		$sourceObj = $this->config->dataSourceObj( $source );
		$data = $sourceObj->getDataPiece( $job->job_syncField );
		if( $data != NULL ) {
			$this->dataFound(
				array(
					'film' => $job->job_filmId,
					'field' => $job->job_syncField,
					'data' => $data
				)
			);
			$data = true;
		} else {
			$data = NULL;
		}

		return $data;
	}

	/**
	 * @param $jobData array
	 */
	private function dataFound( $jobData ) {
		$db = new DatabaseWrapper();

		$film = $db->isFilmPopulated(
			$jobData['film'],
			$jobData['field']
		);

		if( $film === true ) {
			$db->addPropChange(
				$jobData['film'],
				$jobData['field'],
				$jobData['data']
			);
			$flag = 3;
		} else {
			$db->updateFilmField(
				$jobData['film'],
				$jobData['field'],
				$jobData['data']
			);
			$flag = 4;
		}

		$this->flagAdmin(
			array( 'jobId' => $job->job_jobId, 'jobFilm' => $job->job_filmId ),
			// Status code.
			// 3 = change proposed.
			// 4 = change added to field directly.
			$flag
		);
	}
}