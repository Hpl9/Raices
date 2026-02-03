<?php
declare(strict_types=1);

final class Producto
{
    public function __construct(
        private ?int $id,                 // generado por la BD
        private string $nombre,
        private string $categoria,
        private ?string $descripcion,
        private float $precio,
        private int $stock,
        private string $unidadMedida,     // kg | g | unidad
        private ?string $imagenUrl,
        private ?string $procedencia,
        private int $usuarioId             // admin logueado (NO cambia)
    ) {}

    // -----------------------Getters----------------------
    
    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCategoria(): string { return $this->categoria; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getPrecio(): float { return $this->precio; }
    public function getStock(): int { return $this->stock; }
    public function getUnidadMedida(): string { return $this->unidadMedida; }
    public function getImagenUrl(): ?string { return $this->imagenUrl; }
    public function getProcedencia(): ?string { return $this->procedencia; }
    public function getUsuarioId(): int { return $this->usuarioId; }

    
    //------------------ Setters------------------------
    
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function setPrecio(float $precio): void
    {
        if ($precio < 0) {
            throw new InvalidArgumentException('El precio no puede ser negativo');
        }
        $this->precio = $precio;
    }

    public function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new InvalidArgumentException('El stock no puede ser negativo');
        }
        $this->stock = $stock;
    }

    public function setUnidadMedida(string $unidadMedida): void
    {
        $this->unidadMedida = $unidadMedida;
    }

    public function setImagenUrl(?string $imagenUrl): void
    {
        $this->imagenUrl = $imagenUrl;
    }

    public function setProcedencia(?string $procedencia): void
    {
        $this->procedencia = $procedencia;
    }

    
}
