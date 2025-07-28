<div class="modal fade" id="selectCodeBlockModal" tabindex="-1" role="dialog" aria-labelledby="selectCodeBlockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectCodeBlockModalLabel">Select Code Block</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="codeBlockSearch">Search Code Blocks</label>
                    <input type="text" class="form-control" id="codeBlockSearch" placeholder="Search by description or content">
                </div>
                <div class="list-group" id="codeBlockList">
                    <!-- Code blocks will be loaded here via AJAX -->
                </div>
                <div class="text-center mt-3">
                    <button id="loadMoreCodeBlocks" class="btn btn-info btn-sm">Load More</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
