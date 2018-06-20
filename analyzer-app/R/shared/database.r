library(RMySQL)

#if(length(dbListConnections(RMySQL::MySQL())) == 0){#se não existe conexão aberta, crie uma
  con <- dbConnect(RMySQL::MySQL(), user='homestead', password='secret', dbname='tweets', host='localhost', port = 33060)
#}else{
#  con <- dbListConnections(RMySQL::MySQL())[[1]]
#}

loadNewConnection <- function(){
  return(dbConnect(RMySQL::MySQL(), user='homestead', password='secret', dbname='tweets', host='localhost', port = 33060))
}