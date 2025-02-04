<?php

declare(strict_types=1);

class SessionHandlerAdapter implements SessionHandlerInterface
{

	public function __construct(
		private CakeSessionHandlerInterface $cakeSessionHandler
	) {
	}

	public function close()
	{
		return $this->cakeSessionHandler->close();
	}

	public function destroy(string $id)
	{
		return $this->cakeSessionHandler->destroy($id);
	}

	public function gc(int $max_lifetime)
	{
		return $this->cakeSessionHandler->gc($max_lifetime);
	}

	public function open(string $path, string $name)
	{
		//Cake interface ignores these parameters.
		return $this->cakeSessionHandler->open();
	}

	public function read(string $id)
	{
		return $this->cakeSessionHandler->read($id);
	}

	public function write(string $id, string $data)
	{
		return $this->cakeSessionHandler->write($id, $data);
	}
}
