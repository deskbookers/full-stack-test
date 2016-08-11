<?php

class Response extends Kohana_Response
{
	protected $json_ = null;

	public function json($data = null)
	{
		if (func_num_args() == 0)
		{
			return $this->json_;
		}
		else
		{
			$this->json_ = $data;
			$this
				->headers('Content-Type', 'application/json')
				->body(json_encode($this->json_))
			;
		}
		return $this;
	}
}
