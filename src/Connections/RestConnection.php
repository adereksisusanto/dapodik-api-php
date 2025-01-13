<?php

namespace Adereksisusanto\DapodikAPI\Connections;

use Adereksisusanto\DapodikAPI\Collections\Collection;
use Adereksisusanto\DapodikAPI\Contracts\RestInterface;
use Adereksisusanto\DapodikAPI\Exceptions\DapodikException;
use Adereksisusanto\DapodikAPI\Response;
use GuzzleHttp\TransferStats;

/**
 *
 */
class RestConnection extends Connection implements RestInterface
{
    public function __construct(array $config, array $auth)
    {
        parent::__construct($config);
        $this->setConfig('path', '/rest');

        $this->connect($auth);
    }

    /**
     * @param array $auth
     * @return void
     * @throws DapodikException
     */
    protected function connect(array $auth)
    {
        $this->setFormParams("username", $auth['username']);
        $this->setFormParams("password", $auth['password']);
        $this->setFormParams("semester_id", $this->getSemester('key'));
        $this->setFormParams("rememberme", 'on');

        $rolePage = $this->request('POST', '/roleperan', [
            'on_stats' => function (TransferStats $stats) {
                if ($stats->hasResponse()) {
                    if ($stats->getResponse()->getStatusCode() === 302) {
                        $location = preg_match("/\/#(\S*)/", $stats->getResponse()->getHeader('Location')[0], $match) ? $match[1] : null;
                        if ($location == 'PasswordSalah') {
                            throw new DapodikException("Password yang Anda masukkan salah!");
                        }
                        if ($location == 'PenggunaTidakTerdaftar') {
                            throw new DapodikException("Email yang Anda masukkan tidak terdaftar pada aplikasi DapodikRest! Mohon gunakan email lain.");
                        }
                        if ($location == 'SemesterTidakAktif') {
                            throw new DapodikException("Semester telah dinonaktifkan.");
                        }
                        if ($location == 'RoleBelumDitentukan') {
                            throw new DapodikException("Role pengguna belum ditentukan! Untuk akun GTK mohon menghubungi Operator Sekolah dan untuk akun Operator Sekolah mohon menghubungi Dinas Pendidikan. Terima Kasih.");
                        }
                        if ($location == 'NotPermission') {
                            throw new DapodikException("Maaf, sekolah anda tidak diizinkan menggunakan Installer Aplikasi ini.");
                        }
                    }
                } else {
                    throw new DapodikException($stats->getHandlerErrorData());
                }
            },
        ])->getBody()->getContents();

        $links = preg_match_all("/<a.+?href=['\"]\/login\?(\S*)['\"].*?<\/a>/", $rolePage, $matches) ? $matches[1] : [];
        $roles = preg_match_all("/<span>Peran: (.*?)<\/span>/", $rolePage, $matches) ? $matches[1] : [];

        if (((count($links) !== 1) && (count($roles) !== 1)) || strtolower($roles[0]) !== 'operator sekolah') {
            throw new DapodikException("Pastikan Akun anda adalah akun Operator Sekolah!");
        }

        $this->forgeOptions('form_params');

        $this->request("GET", "/login?{$links[0]}");

        $this->checkKodeRegistrasi($auth['kode_registrasi']);

        $sekolah_id = $this->sekolah()->get(0)['sekolah_id'];
        $this->setQuery("_dc", time().substr(str_shuffle("0123456789"), 0, 3));
        $this->setQuery("sekolah_id", $sekolah_id);

    }

    /**
     * @param string $kode_registrasi
     * @return void
     * @throws DapodikException
     */
    protected function checkKodeRegistrasi(string $kode_registrasi)
    {
        $this->setFormParams("koreg", $kode_registrasi);
        $status = json_decode($this->request('POST', '/cekkoreg')->getBody()->getContents());
        $this->forgeOptions('form_params');
        if (! $status->success) {
            $this->logout();

            throw new DapodikException($status->message);
        }
    }

    /**
     * @throws DapodikException
     */
    protected function logout()
    {
        $this->request('GET', '/destauth')->getBody()->getContents();

        return null;
    }

    /**
     * Get Sekolah
     *
     * @param array $query
     * @return Collection
     * @throws DapodikException
     */
    public function sekolah(array $query = []): Collection
    {
        $uri = $this->getConfig('path').'/sekolah';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * @throws DapodikException
     */
    public function __destruct()
    {
        return $this->logout();
    }

    /**
     * Get Peserta Didik
     *
     * @param array $query
     * @return Collection
     * @throws DapodikException
     */
    public function pd(array $query = []): Collection
    {
        if (! isset($query['jenis'])) {
            $query['jenis'] = '';
        }

        return match ($query['jenis']) {
            "aktif" => new Collection($this->pdt()->toArray()),
            "keluar" => new Collection($this->pdk()->toArray()),
            default => new Collection(array_merge($this->pdt()->toArray(), $this->pdk()->toArray())),
        };
    }

    /**
     * Get Peserta Didik
     *
     * @param array $query
     * @return Collection
     * @throws DapodikException
     */
    protected function pdt(array $query = []): Collection
    {
        $this->setQuery('limit', 100000);
        $this->setQuery('pd_module', 'pdterdaftar');
        $uri = $this->getConfig('path').'/PesertaDidik';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Peserta Didik
     *
     * @param array $query
     * @return Collection
     * @throws DapodikException
     */
    protected function pdk(array $query = []): Collection
    {
        $this->setQuery('limit', 100000);
        $this->setQuery('pd_module', 'pdkeluar');
        $uri = $this->getConfig('path').'/PesertaDidik';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }
}
