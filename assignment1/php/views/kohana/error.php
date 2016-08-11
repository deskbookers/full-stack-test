<?= $class ?> [ <?= $code ?> ]:

<?= $message ?>


<?= Debug::path($file) ?> [ <?= $line ?> ]
<?php foreach ($trace as $i => $call): ?>
<?= $i + 1 ?>. <?= Debug::path(Arr::get($call, 'file')) ?>:<?= Arr::get($call, 'line') ?> :: <?= strlen(Arr::get($call, 'class')) > 0 ? Arr::get($call, 'class') . '::' : '' ?><?= Arr::get($call, 'function') ?>(<?= implode(', ', array_map(
	function($arg)
	{
		if ($arg instanceof ORM)
		{
			return get_class($arg) . ($arg->loaded() ? '(' . $arg->id . ')' : '');
		}
		else if (is_object($arg))
		{
			return get_class($arg);
		}
		else if (is_array($arg))
		{
			if (count($arg) == 0)
			{
				return '[]';
			}
			else
			{
				return 'array(length=' . count($arg) . ')';
			}
		}
		else if (is_string($arg))
		{
			return var_export(substr($arg, 0, 50) . (strlen($arg) > 50 ? '...' : ''), true);
		}
		else
		{
			return var_export($arg, true);
		}
	},
	(array) Arr::get($call, 'args')
)) ?>)
<?php endforeach; ?>
