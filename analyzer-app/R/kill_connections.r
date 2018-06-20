library(RMySQL)  

killDbConnections <- function () {

  all_cons <- dbListConnections(MySQL())
  length(all_cons)> 0

  print(all_cons)

  for(con in all_cons)
    +  dbDisconnect(con)

  print(paste(length(all_cons), " connections killed."))

}

killDbConnections()