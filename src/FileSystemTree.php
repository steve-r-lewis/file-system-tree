<?php declare(strict_types=1);

/**
 * @copyright 2021© Steve Lewis, The Module Factory Ltd.
 *
 * @author Steve R. Lewis, <steve@themodulefactory.com>
 *
 * A small package built for a private project to manage text read from a file from which a text object is created.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 * You should have received a copy of the MIT License along with this program, alternatively you can get a copy here;
 * @license https://github.com/TheModuleFactory/file-system-tree/blob/main/LICENSE
 *
 * For more information regarding Open Source Licensing you can visit;
 *
 *     <https://www.mit.edu/~amini/LICENSE.md>
 *     <https://opensource.org/licenses/MIT>
 *
 * The Module Factory Ltd. Company Number; 09989173, <https://www.themodulefactory.com>
 */

namespace FileSystemTree;

class FileSystemTree
{
    /**
     * @var array|null
     */
    private array $fileSystemTree;
    /**
     * @var array
     */
    private array $listRootFolders;
    /**
     * @var array
     */
    private array $listFoldersAndFiles;
    /**
     * @var array
     */
    private array $listFolders;
    /**
     * @var array
     */
    private array $listFiles;

    /**
     * FileSystemTree constructor.
     * @param string $dirRead
     */
    public function __construct(string $dirRead = DIRECTORY_SEPARATOR)
    {
        $this->fileSystemTree = $this->populateObject(0, $dirRead);
    }

    /**
     * @return array|null
     */
    public function getFileSystemTree(): ?array
    {
        return $this->fileSystemTree;
    }

    /**
     * @return array
     */
    public function getListRootFolders(): array
    {
        return $this->listRootFolders;
    }

    /**
     * @return array
     */
    public function getListFoldersAndFiles(): array
    {
        return $this->listFoldersAndFiles;
    }

    /**
     * @return array
     */
    public function getListFolders(): array
    {
        return $this->listFolders;
    }

    /**
     * @return array
     */
    public function getListFiles(): array
    {
        return $this->listFiles;
    }

    /**
     * @param int $depth
     * @param string $baseDir
     * @param string $fileSystemList
     * @return array|null
     */
    private function populateObject(int $depth, string $baseDir, string $fileSystemList = ""): ?array
    {
        $fileSys = scandir($baseDir);
        $recursFilesSys = array();

        $callDepth = $depth + 1;

        foreach ($fileSys as $key => $fileSystemItem)
        {
            if (!in_array($fileSystemItem, array(".", ".."))) {
                $loopInitDir = $baseDir . DIRECTORY_SEPARATOR . $fileSystemItem;
                $loopList = $fileSystemList . DIRECTORY_SEPARATOR . $fileSystemItem;

                if ($callDepth == 1) {
                    $this->listRootFolders[] = $fileSystemItem;
                }

                if (is_dir($baseDir . DIRECTORY_SEPARATOR . $fileSystemItem)) {
                    if (!$this->isDirEmpty($loopInitDir)) {
                        $recursFilesSys[$fileSystemItem] = $this->populateObject($callDepth, $loopInitDir, $loopList);
                    } else {
                        $this->listFoldersAndFiles[] = $loopInitDir;
                        $this->listFolders[] = $loopInitDir;
                    }
                } else {
                    $this->listFoldersAndFiles[] = $loopInitDir;
                    $this->listFiles[] = $loopInitDir;
                    $recursFilesSys[] = $fileSystemItem;
                }
            }
        }
        return $recursFilesSys;
    }

    /**
     * @param $dirTestForEmpty
     * @return bool|null
     */
    private function isDirEmpty($dirTestForEmpty): ?bool
    {
        if (!is_readable($dirTestForEmpty)) return NULL;
        return (count(scandir($dirTestForEmpty)) == 2);
    }
}