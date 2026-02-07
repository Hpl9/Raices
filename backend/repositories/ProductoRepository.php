<?php


require_once __DIR__ . '/../Database/conexion.php';
require_once __DIR__ . '/../Entities/Producto.php';

final class ProductoRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = db();
    }

    
    //---------------- LISTAR (admin)---------------------------

    public function findAllConSocio(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                p.id,
                p.nombre,
                p.categoria,
                p.descripcion,
                p.precio,
                p.stock,
                p.unidad_medida,
                p.imagen_url,
                p.procedencia,
                p.usuario_id,
                CONCAT(u.nombre, ' ', u.apellido) AS socio
            FROM productos p
            INNER JOIN usuarios u ON u.id = p.usuario_id
            ORDER BY p.categoria, p.nombre
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        //---------------- BUSCAR POR ID--------------------------------
 
    public function findById(int $id, int $usuarioId): ?Producto
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM productos
            WHERE id = :id AND usuario_id = :usuario_id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id,
            'usuario_id' => $usuarioId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Producto(
            (int)$row['id'],
            $row['nombre'],
            $row['categoria'],
            $row['descripcion'],
            (float)$row['precio'],
            (int)$row['stock'],
            $row['unidad_medida'],
            $row['imagen_url'],
            $row['procedencia'],
            (int)$row['usuario_id']
        );
    }

  
         //----------------CREAR---------------------------
   
    public function create(Producto $p): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO productos
            (nombre, categoria, descripcion, precio, stock, unidad_medida, imagen_url, procedencia, usuario_id)
            VALUES
            (:nombre, :categoria, :descripcion, :precio, :stock, :unidad, :imagen, :procedencia, :usuario_id)
        ");

        $stmt->execute([
            'nombre' => $p->getNombre(),
            'categoria' => $p->getCategoria(),
            'descripcion' => $p->getDescripcion(),
            'precio' => $p->getPrecio(),
            'stock' => $p->getStock(),
            'unidad' => $p->getUnidadMedida(),
            'imagen' => $p->getImagenUrl(),
            'procedencia' => $p->getProcedencia(),
            'usuario_id' => $p->getUsuarioId(),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

  
        //---------------- ACTUALIZAR ---------------------------
    
    public function update(Producto $p): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE productos
            SET
                nombre = :nombre,
                categoria = :categoria,
                descripcion = :descripcion,
                precio = :precio,
                stock = :stock,
                unidad_medida = :unidad,
                imagen_url = :imagen,
                procedencia = :procedencia
            WHERE id = :id
              AND usuario_id = :usuario_id
        ");

        return $stmt->execute([
            'id' => $p->getId(),
            'nombre' => $p->getNombre(),
            'categoria' => $p->getCategoria(),
            'descripcion' => $p->getDescripcion(),
            'precio' => $p->getPrecio(),
            'stock' => $p->getStock(),
            'unidad' => $p->getUnidadMedida(),
            'imagen' => $p->getImagenUrl(),
            'procedencia' => $p->getProcedencia(),
            'usuario_id' => $p->getUsuarioId(),
        ]);
    }

     
         //---------------- ELIMINAR -------------------------------------
  
    public function delete(int $id): bool
{
    $stmt = $this->pdo->prepare("
        DELETE FROM productos
        WHERE id = :id
    ");

    return $stmt->execute([
        'id' => $id
    ]);
}
}
