<?php
class CodebaseShell extends Shell {
/**
 * Calculates Time for the repository this shell is run in and prints to screen
 *
 * @return void
 * @access public
 */
	function main() {
		$committerName = '';
		$committer = null;
		$totalTime = 0;
		if (isset($this->args[0])) {
			foreach ($this->args as $key => $anArg) {
				if ($key === (count($this->args) - 1)) {
					$committerName .= $anArg;
				} else {
					$committerName .= $anArg . " ";
				}
			}
			$committer = " --committer='{$committerName}'";
		}

		$this->out("Running: git --no-pager log --all-match --pretty=oneline --grep=[0-9].{$committer}");
		$this->out('');
		$output = shell_exec("git --no-pager log --all-match --pretty=oneline --grep=[0-9].{$committer}");

		$this->out('Commits with CodebaseHQ times:');
		$this->out($output);

		preg_match_all("{T[0-9]+}", $output, &$matches);

		foreach($matches['0'] as $match) {
			preg_match_all("{[0-9]+}", $match, &$out);
			$totalTime += $out['0']['0'];
		}

		$hours = floor($totalTime / 60);
		$mins = $totalTime % 60;
		if (isset($this->args[0])) {
			$this->out("Committer {$committerName} took approximately {$hours} Hours {$mins} Minutes ");
		} else {
			$this->out("Current project has taken a total time of {$hours} Hours {$mins} Minutes ");
		}
	}

/**
 * Displays help contents
 *
 * @return void
 * @access public
 */
	function help() {
		$this->out('Jose Diaz-Gonzalez. CodebaseHQ Shell - http://josediazgonzalez.com');
		$this->hr();
		$this->out('This shell calculates total time in a given project, per committer or as a repository, based upon CodebaseHQ\'s notation');
		$this->out('');
		$this->hr();
		$this->out("Usage: cake codebase");
		$this->out("       cake codebase author-name");
		$this->out('');
	}
}
?>