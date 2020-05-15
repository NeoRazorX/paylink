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
namespace FacturaScripts\Plugins\PayLink\Controller;

use FacturaScripts\Core\Base\Controller;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;
use FacturaScripts\Plugins\PayLink\Model\ReciboCliente;

/**
 * Description of PayLink
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class PayLink extends Controller
{

    /**
     *
     * @var ReciboCliente
     */
    public $receipt;

    /**
     * 
     * @return array
     */
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'sales';
        $data['title'] = 'paylink';
        $data['showonmenu'] = false;
        return $data;
    }

    public function privateCore(&$response, $user, $permissions)
    {
        parent::privateCore($response, $user, $permissions);
        $this->loadReceipt();
    }

    public function publicCore(&$response)
    {
        parent::publicCore($response);
        $this->loadReceipt();
    }

    protected function loadReceipt()
    {
        $this->receipt = new ReciboCliente();
        $code = $this->request->get('code', '');
        $where = [new DataBaseWhere('paylinkcode', $code)];
        if (false === $this->receipt->loadFromCode('', $where)) {
            $this->toolBox()->i18nLog()->warning('record-not-found');
            return;
        }

        /// captura de datos
        ;

        /// marcar recibo como pagado
        ///$this->receipt->pagado = true;
        ///$this->receipt->save();
    }
}
