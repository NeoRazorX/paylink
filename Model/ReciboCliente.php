<?php
/**
 * This file is part of PayLink plugin for FacturaScripts
 * Copyright (C) 2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\PayLink\Model;

use FacturaScripts\Core\Model\ReciboCliente as ParentModel;

/**
 * Description of ReciboCliente
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class ReciboCliente extends ParentModel
{

    /**
     *
     * @var string
     */
    public $paylinkcode;

    /**
     * 
     * @param string $type
     * @param string $list
     *
     * @return string
     */
    public function url(string $type = 'auto', string $list = 'ListFacturaCliente?activetab=List')
    {
        if (empty($this->primaryColumnValue()) || $this->pagado) {
            return parent::url($type, $list);
        }

        if (empty($this->paylinkcode)) {
            $this->paylinkcode = $this->getNewPayLinkCode();
            $this->save();
        }

        return 'http://localhost/PayLink?code=' . $this->paylinkcode;
    }

    /**
     * 
     * @return string
     */
    private function getNewPayLinkCode(): string
    {
        return $this->toolBox()->utils()->randomString(99);
    }
}
