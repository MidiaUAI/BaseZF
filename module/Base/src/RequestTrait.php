<?php

namespace Base;

trait RequestTrait
{

    public function isPost()
    {
        return $this->getRequest()->isPost();
    }

    public function fromPost($param = null, $default = null)
    {
        $valor = $this->params()->fromPost($param, false);
        if (is_array($valor)) {
            return $this->params()->fromPost($param);
        }
        if (isset($valor) && strlen($valor) > 0) {
            return $valor;
        }
        return $default;
    }

    public function fromQuery($param = null, $default = null)
    {
        $valor = $this->params()->fromQuery($param, false);
        if (is_array($valor)) {
            return $this->params()->fromQuery($param);
        }
        if (isset($valor) && strlen($valor) > 0) {
            return $valor;
        }
        return $default;
    }

    public function urlBaseSite($url)
    {
        return str_replace('/index.php', '', $url);
    }

    public function getHost()
    {
        return $this->getRequest()->getUri()->getHost();
    }

    public function getBaseUrl()
    {
        return 'http://' . $this->getRequest()->getUri()->getHost();
    }

    /**
     * Recupera a página de origem, a anterior
     * @example $this->getReferer();
     */
    public function getReferer()
    {
        return $this->getRequest()->getHeader('Referer')->uri()->getPath();
    }
}
